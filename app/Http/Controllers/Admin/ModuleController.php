<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Traits\GradeCalculator;

class ModuleController extends Controller
{
    use GradeCalculator;

    public function index(Branch $branch)
    {
        $modules = $branch->modules;
        return view('admin-section.modules.index', compact('branch', 'modules'));
    }

    public function store(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'factor' => 'required|numeric'
        ]);

        $branch->modules()->create([
            'name' => $validated['name'],
            'factor' => $validated['factor']
        ]);

        return redirect()->route('admin.branches.modules.index', $branch)
            ->with('success', 'Module created successfully');
    }

    public function edit(Branch $branch, Module $module)
    {
        return view('admin-section.modules.edit', compact('branch', 'module'));
    }

    public function update(Request $request, Branch $branch, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'factor' => 'required|numeric|min:0.01|max:99.99'
        ]);

        $module->update($validated);

        return redirect()->route('admin.branches.modules.index', $branch)
            ->with('success', 'Module updated successfully');
    }

    public function destroy(Branch $branch, Module $module)
    {
        $module->delete();

        return redirect()->route('admin.branches.modules.index', $branch)
            ->with('success', 'Module deleted successfully');
    }

    public function show(Branch $branch, Module $module)
    {
        $module->load(['controls.grades']);
        
        $moduleGrades = $module->controls->flatMap->grades;
        $moduleGrade = $this->calculateModuleGrade($moduleGrades);
        $metrics = $this->getPassFailMetrics($moduleGrades);
        
        return view('admin-section.modules.show', compact('branch', 'module', 'moduleGrade', 'metrics'));
    }

    /**
     * Show the form for creating a new module.
     */
    public function create(Branch $branch)
    {
        return view('admin-section.modules.create', compact('branch'));
    }
} 