<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Control;
use Illuminate\Http\Request;
use App\Traits\GradeCalculator;

class ControlController extends Controller
{
    use GradeCalculator;

    public function index(Module $module)
    {
        $controls = $module->controls;
        return view('admin-section.controls.index', compact('module', 'controls'));
    }

    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:exam,project,assignment',
            'factor' => 'required|numeric|min:0.01|max:99.99',
            'max_grade' => 'required|numeric|min:1|max:100'
        ]);

        $module->controls()->create($validated);

        return redirect()->route('admin.modules.controls.index', $module)
            ->with('success', 'Control created successfully');
    }

    public function edit(Module $module, Control $control)
    {
        return view('admin-section.controls.edit', compact('module', 'control'));
    }

    public function update(Request $request, Module $module, Control $control)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:exam,project,assignment',
            'factor' => 'required|numeric|min:0.01|max:99.99',
            'max_grade' => 'required|numeric|min:1|max:100'
        ]);

        $control->update($validated);

        return redirect()->route('admin.modules.controls.index', $module)
            ->with('success', 'Control updated successfully');
    }

    public function destroy(Module $module, Control $control)
    {
        $control->delete();

        return redirect()->route('admin.modules.controls.index', $module)
            ->with('success', 'Control deleted successfully');
    }

    public function show(Module $module, Control $control)
    {
        $control->load(['grades']);
        
        $metrics = $this->getPassFailMetrics($control->grades);
        $grades = $control->grades->map(function($grade) {
            $grade->weighted_grade = $this->calculateControlWeightedGrade($grade);
            return $grade;
        });
        
        return view('admin-section.controls.show', compact('module', 'control', 'grades', 'metrics'));
    }

    /**
     * Show the form for creating a new control.
     */
    public function create(Module $module)
    {
        return view('admin-section.controls.create', compact('module'));
    }
} 