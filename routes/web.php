<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ControlController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\SubjectController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Dashboard
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Admin management routes
    Route::get('/admin-management', [AdminController::class, 'index'])->name('admin.management');
    Route::get('/admin-management/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin-management', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin-management/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin-management/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin-management/{id}', [AdminController::class, 'destroy'])->name('admin.delete');

    // Class-Teacher and Class-Student relationships
    Route::delete('/classes/{class}/teachers/{teacher}', [ClassController::class, 'removeTeacher'])->name('classes.teachers.remove');
    Route::delete('/classes/{class}/students/{student}', [ClassController::class, 'removeStudent'])->name('classes.students.remove');
});

// Student Dashboard
Route::middleware('student')->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard.student');
    Route::get('/student/grades', [App\Http\Controllers\Student\GradeController::class, 'index'])->name('student.grades');
    
    // Student Profile Routes
    Route::get('/student/profile', [App\Http\Controllers\Student\StudentProfileController::class, 'show'])->name('student.profile');
    Route::put('/student/profile', [App\Http\Controllers\Student\StudentProfileController::class, 'update'])->name('student.profile.update');

    // Student Subjects Routes
    Route::get('/student/subjects', [App\Http\Controllers\Student\SubjectController::class, 'index'])->name('student.subjects.index');
    Route::get('/student/subjects/{id}', [App\Http\Controllers\Student\SubjectController::class, 'show'])->name('student.subjects.show');
});

// Teacher Dashboard
Route::middleware(['auth', 'teacher'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'dashboard'])->name('dashboard.teacher');
    Route::get('/teacher/classes', [TeacherController::class, 'classes'])->name('teacher.classes');
    Route::get('/teacher/classes/{class}', [ClassController::class, 'teacherClassDetails'])->name('teacher.classes.details');
    Route::get('/teacher/profile', function () {
        return view('teacher-section.profile.profile');
    })->name('teacher.profile');
    Route::get('/teacher/classes/{class}', [TeacherController::class, 'showClass'])->name('teacher.classes.show');
});

// Teacher, Student and Class Management
Route::resource('teachers', TeacherController::class);
Route::resource('students', StudentController::class);
Route::resource('classes', ClassController::class);

// Profile management
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/profile', function () {
        return view('admin-section.profile.profile');
    })->name('profile.admin');

    Route::get('/super-admin/profile', function () {
        return view('admin.profile');
    })->name('profile.super_admin');
});

Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update.picture');

// Schedule Management Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('schedules', ScheduleController::class);
});

Route::get('/schedules/{schedule}/pdf', [ScheduleController::class, 'downloadPdf'])->name('schedules.pdf');

// teacher section  Grade Management Routes
Route::prefix('teacher-section')->name('teacher-section.')->middleware(['auth', 'teacher'])->group(function () {
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/class/{class}', [GradeController::class, 'showClass'])->name('grades.class');
    Route::get('/grades/class/{class}/student/{student}', [GradeController::class, 'showStudent'])->name('grades.student');
    Route::post('/grades/class/{class}/student/{student}', [GradeController::class, 'storeGrades'])->name('grades.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Branches
    Route::resource('branches', BranchController::class);
    
    // Modules (nested under branches)
    Route::resource('branches.modules', ModuleController::class);
    
    // Controls (nested under modules)
    Route::resource('modules.controls', ControlController::class)->except(['show']);
});

// Teacher section routes
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // Grade management routes
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/class/{class}', [GradeController::class, 'showClass'])->name('grades.class');
    Route::get('/grades/class/{class}/student/{student}', [GradeController::class, 'showStudent'])->name('grades.student');
    Route::post('/grades/class/{class}/student/{student}', [GradeController::class, 'storeGrades'])->name('grades.store');
});

Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/schedule/{class}', [App\Http\Controllers\Student\ScheduleController::class, 'show'])
        ->name('student.schedule');
    Route::get('/schedule/{class}/download', [App\Http\Controllers\Student\ScheduleController::class, 'download'])
        ->name('student.schedule.download');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/schedule/{class}', [ScheduleController::class, 'teacherSchedule'])
        ->name('teacher.schedule');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Schedule routes
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::get('/schedules/class/{class}/teachers', [ScheduleController::class, 'getTeacherClassSubjects']);
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
});

