<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Traits\GradeCalculator;

class BranchController extends Controller
{
    use GradeCalculator;

    public function index()
    {
        $branches = Branch::with(['modules', 'students'])->get();
        return view('admin-section.branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:branches'
        ]);

        Branch::create($validated);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch created successfully');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch deleted successfully');
    }

    public function show(Branch $branch)
    {
        $branch->load(['modules.controls.grades', 'students']);
        
        // Calculate module metrics
        $moduleMetrics = [];
        foreach ($branch->modules as $module) {
            $moduleGrades = $module->controls->flatMap->grades;
            $moduleMetrics[$module->id] = [
                'grade' => $moduleGrades->isNotEmpty() ? 
                    $this->calculateModuleGrade($moduleGrades) : 0,
                'metrics' => $this->getPassFailMetrics($moduleGrades),
                'max_grade' => $module->controls->max('max_grade') ?? 20 // Default to 20 if no controls
            ];
        }
        
        // Calculate branch overall metrics
        $branchGrade = $this->calculateBranchGrade($branch->modules);
        $allGrades = $branch->modules->flatMap->controls->flatMap->grades;
        $metrics = $this->getPassFailMetrics($allGrades);

        return view('admin-section.branches.show', compact(
            'branch', 
            'branchGrade', 
            'metrics',
            'moduleMetrics'
        ));
    }
} 