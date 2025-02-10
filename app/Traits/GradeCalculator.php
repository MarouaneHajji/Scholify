<?php
namespace App\Traits;

trait GradeCalculator
{
    /**
     * Calculate the grade for a module on a scale of 10.
     */
    public function calculateModuleGrade($moduleGrades)
    {
        $moduleWeightedSum = 0;
        $moduleFactorSum = 0;

        foreach ($moduleGrades as $grade) {
            // First normalize to scale of 10
            $normalizedGrade = ($grade->grade_value / $grade->control->max_grade) * 10;
            // Apply control factor
            $moduleWeightedSum += $normalizedGrade * $grade->control->factor;
            $moduleFactorSum += $grade->control->factor;
        }

        // Get average on scale of 10
        return $moduleFactorSum > 0 ? $moduleWeightedSum / $moduleFactorSum : 0;
    }

    /**
     * Calculate the overall grade for a branch on a scale of 10.
     */
    public function calculateBranchGrade($modules)
    {
        $totalWeightedSum = 0;
        $totalModuleFactors = 0;

        foreach ($modules as $module) {
            if (!$module->relationLoaded('grades')) {
                $module->load(['controls.grades']);
            }

            $moduleGrades = $module->controls->flatMap->grades;

            if ($moduleGrades && $moduleGrades->isNotEmpty()) {
                $moduleGrade = $this->calculateModuleGrade($moduleGrades); // Already normalized to 10
                $totalWeightedSum += $moduleGrade * $module->factor;
                $totalModuleFactors += $module->factor;
            }
        }

        return $totalModuleFactors > 0 ? ($totalWeightedSum / $totalModuleFactors) : 0;
    }

    /**
     * Calculate the weighted grade for a control.
     * Returns the weighted grade on the same scale as the max_grade.
     */
    public function calculateControlWeightedGrade($grade)
    {
        // First normalize to scale of 10
        $normalizedGrade = ($grade->grade_value / $grade->control->max_grade) * 10;
        // Apply factor and convert back to original scale
        return ($normalizedGrade * $grade->control->factor) / 10 * $grade->control->max_grade;
    }

    /**
     * Calculate pass/fail metrics based on normalized grades.
     */
    public function getPassFailMetrics($grades)
    {
        if (!$grades) {
            return [
                'total' => 0,
                'passed' => 0,
                'successRate' => 0,
            ];
        }

        $total = $grades->count();
        $passed = $grades->filter(function ($grade) {
            // Pass if normalized grade >= 5/10
            $normalizedGrade = ($grade->grade_value / $grade->control->max_grade) * 10;
            return $normalizedGrade >= 5;
        })->count();

        $successRate = $total > 0 ? ($passed / $total) * 100 : 0;

        return [
            'total' => $total,
            'passed' => $passed,
            'successRate' => $successRate,
        ];
    }
}
