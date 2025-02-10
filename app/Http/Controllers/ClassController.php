<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Branch;
use App\Models\Module;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    // Show all classes
    public function index()
    {
        $classes = Classe::with(['teachers', 'students'])
            ->get()
            ->map(function ($class) {
                $class->teacherCount = $class->teachers->unique()->count();
                $class->studentCount = $class->students->count();
                return $class;
            });
        
        return view('admin-section.classes.index', compact('classes'));
    }

    // Show form to create a new class
    public function create()
    {
        $teachers = Teacher::all();
        $students = Student::all();
        $branches = Branch::with('modules')->get();
        return view('admin-section.classes.create', compact('teachers', 'branches', 'students'));
    }

    // Store a new class
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'module_teachers' => 'nullable|array',
            'module_teachers.*' => 'nullable|exists:teachers,id',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id'
        ]);

        $class = Classe::create([
            'name' => $validated['name'],
            'branch_id' => $validated['branch_id']
        ]);

        // Attach teachers with their modules (if any)
        if ($request->has('module_teachers')) {
            foreach ($request->module_teachers as $moduleId => $teacherId) {
                if ($teacherId) {
                    $class->teachers()->attach($teacherId, [
                        'module_id' => $moduleId
                    ]);
                }
            }
        }

        // Attach students if any are selected
        if ($request->has('students')) {
            $class->students()->attach($request->students);
        }

        return response()->json([
            'success' => true,
            'message' => 'Class created successfully',
            'redirect' => route('classes.index')
        ]);
    }

    // Show form to edit a class
    public function edit(Classe $class)
    {
        $class->load(['teachers', 'students']);
        $teachers = Teacher::all();
        $students = Student::all();
        $modules = Module::all();
        
        return view('admin-section.classes.edit', compact('class', 'teachers', 'students', 'modules'));
    }

    // Update a class
    public function update(Request $request, Classe $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
            'teacher_modules' => 'nullable|array',
            'teacher_modules.*' => 'array',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id'
        ]);

        $class->update([
            'name' => $request->name
        ]);

        // First detach all existing teacher-module relationships
        $class->teachers()->detach();
        
        // Attach new teacher-module relationships
        if ($request->has('teachers') && $request->has('teacher_modules')) {
            foreach ($request->teachers as $teacherId) {
                if (isset($request->teacher_modules[$teacherId])) {
                    foreach ($request->teacher_modules[$teacherId] as $moduleId) {
                        $class->teachers()->attach($teacherId, [
                            'module_id' => $moduleId
                        ]);
                    }
                }
            }
        }

        // Update students
        $class->students()->sync($request->students ?? []);

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully');
    }

    // Delete a class
    public function destroy(Classe $class)
    {
        try {
            $class->students()->detach(); // Detach all students
            $class->delete();
            return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('classes.index')->with('error', 'Failed to delete class. Please try again.');
        }
    }

    public function show(Classe $class)
    {
        // Load relationships and ensure unique teachers with their modules
        $class->load(['students']);
        
        // Get unique teachers with their modules
        $teachers = $class->teachers()
            ->with('modules')
            ->get()
            ->unique('id')
            ->map(function ($teacher) use ($class) {
                $teacher->classModules = $teacher->modules()
                    ->wherePivot('classe_id', $class->id)
                    ->get();
                return $teacher;
            });

        return view('admin-section.classes.show', compact('class', 'teachers'));
    }

    public function removeTeacher(Classe $class, Teacher $teacher)
    {
        try {
            $class->teachers()->detach($teacher->id);
            return back()->with('success', 'Teacher removed from class successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove teacher.');
        }
    }

    public function removeStudent(Classe $class, Student $student)
    {
        try {
            $class->students()->detach($student->id);
            return back()->with('success', 'Student removed from class successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove student.');
        }
    }

    public function teacherClassDetails(Classe $class)
    {
        $teacher = auth()->user()->teacher;
        
        // Get the modules this teacher teaches in this class
        $modules = $teacher->modules()
            ->wherePivot('classe_id', $class->id)
            ->get();

        // Get paginated students for this class
        $students = $class->students()->paginate(7);

        return view('teacher-section.classes.show', compact('class', 'modules', 'students'));
    }
}