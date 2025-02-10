<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Control;
use App\Models\Grade;
use App\Models\Classe;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        
        // Get unique classes with student count
        $classes = $teacher->classes()
            ->withCount('students')
            ->get()
            ->unique('id')
            ->values();

        return view('teacher-section.grades.index', compact('classes'));
    }

    public function showClass($classId)
    {
        $teacher = auth()->user()->teacher;
        $class = $teacher->classes()
            ->with(['students', 'branch'])
            ->findOrFail($classId);
        
        return view('teacher-section.grades.class', compact('class'));
    }

    public function showStudent($classId, $studentId)
    {
        $teacher = auth()->user()->teacher;
        $class = $teacher->classes()->findOrFail($classId);
        $student = $class->students()->findOrFail($studentId);
        
        // Get only modules assigned to this class instead of all branch modules
        $modules = $class->modules()->with('controls')->get();
        
        // Get existing grades
        $grades = Grade::where('student_id', $student->id)
            ->whereIn('control_id', $modules->flatMap->controls->pluck('id'))
            ->get()
            ->keyBy('control_id');
        
        return view('teacher-section.grades.student', compact('class', 'student', 'modules', 'grades'));
    }

    public function storeGrades(Request $request, $classId, $studentId)
    {
        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0'
        ]);

        $teacher = auth()->user()->teacher;
        $class = $teacher->classes()->findOrFail($classId);
        $student = $class->students()->findOrFail($studentId);

        foreach ($validated['grades'] as $controlId => $gradeValue) {
            $control = Control::findOrFail($controlId);
            
            // Ensure grade doesn't exceed max_grade
            $gradeValue = min($gradeValue, $control->max_grade);
            
            Grade::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'control_id' => $controlId,
                ],
                ['grade_value' => $gradeValue]
            );
        }

        return redirect()
            ->route('teacher-section.grades.student', [$classId, $studentId])
            ->with('success', 'Grades updated successfully');
    }
} 