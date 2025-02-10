@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{
    showTeachers: false,
    showStudents: false,
    selectedTeacher: null
}">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <div class="p-2 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z"/>
                    <path d="M11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Edit Class
                </h1>
                <p class="text-gray-500">Manage class details, teachers, and students</p>
            </div>
            </div>
            <a href="{{ route('classes.index') }}" 
           class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 transition-all duration-200 group">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
            <span class="text-gray-600 group-hover:text-gray-700">Back to Classes</span>
            </a>
    </div>

    <form action="{{ route('classes.update', $class) }}" method="POST">
                @csrf
                @method('PUT')

        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                    <div class="max-w-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class Name</label>
                        <input type="text" name="name" value="{{ $class->name }}" 
                               class="w-full px-4 py-2 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                           required>
                    </div>
                </div>
                </div>

            <!-- Teachers and Modules -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Teachers & Modules</h2>
                        <button type="button" 
                                @click="showTeachers = !showTeachers"
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                            <span x-text="showTeachers ? 'Hide Teachers' : 'Show Teachers'"></span>
                        </button>
                    </div>

                    <div x-show="showTeachers" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="space-y-4">
                        @foreach($teachers as $teacher)
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-50/80 transition-all duration-200"
                                 x-data="{ isSelected: {{ $class->teachers->contains($teacher) ? 'true' : 'false' }} }">
                                <div class="flex items-center justify-between mb-3">
                                    <button type="button" 
                                            class="flex items-center space-x-3 focus:outline-none"
                                            @click="isSelected = !isSelected">
                                        <div class="w-5 h-5 rounded-lg border-2 transition-colors duration-200"
                                             :class="isSelected ? 'bg-blue-600 border-blue-600' : 'border-gray-300 bg-white'">
                                            <svg class="w-full h-full text-white" viewBox="0 0 20 20" fill="currentColor" x-show="isSelected">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">
                                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                                        </span>
                                    </button>
                                    <input type="checkbox" 
                                           name="teachers[]" 
                                           value="{{ $teacher->id }}"
                                           x-model="isSelected"
                                           class="hidden">
                                    <button type="button"
                                            @click="selectedTeacher = selectedTeacher === {{ $teacher->id }} ? null : {{ $teacher->id }}"
                                            class="px-3 py-1 text-sm text-blue-600 hover:text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-50 transition-all duration-200">
                                        Manage Modules
                                    </button>
                                </div>
                                
                                <div x-show="selectedTeacher === {{ $teacher->id }}"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="ml-8 mt-3">
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                        @foreach($modules as $module)
                                            <div class="relative"
                                                 x-data="{ moduleSelected: {{ $class->teachers()->wherePivot('teacher_id', $teacher->id)->wherePivot('module_id', $module->id)->exists() ? 'true' : 'false' }} }">
                                                <button type="button"
                                                        @click="moduleSelected = !moduleSelected"
                                                        class="w-full text-left p-3 rounded-lg border-2 transition-all duration-200"
                                                        :class="moduleSelected ? 'border-blue-400 bg-blue-50' : 'border-gray-200 hover:border-blue-200'">
                                                    <div>
                                                        <span class="block text-sm font-medium text-gray-900">{{ $module->name }}</span>
                                                        <span class="block text-xs text-gray-500 mt-0.5">Factor: {{ $module->factor }}</span>
                                                    </div>
                                                </button>
                                                <input type="checkbox" 
                                                       name="teacher_modules[{{ $teacher->id }}][]"
                                                       value="{{ $module->id }}"
                                                       x-model="moduleSelected"
                                                       class="hidden">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                </div>

            <!-- Students -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Students</h2>
                        <button type="button" 
                                @click="showStudents = !showStudents"
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                            <span x-text="showStudents ? 'Hide Students' : 'Show Students'"></span>
                        </button>
                    </div>

                    <div x-show="showStudents"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0">
                        <div class="mb-4">
                            <input type="text" 
                                   placeholder="Search students..." 
                                   class="w-full px-4 py-2 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                   @input="searchStudents($event.target.value)">
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($students as $student)
                                <div x-data="{ studentSelected: {{ $class->students->contains($student) ? 'true' : 'false' }} }">
                                    <button type="button"
                                            @click="studentSelected = !studentSelected"
                                            class="w-full flex items-center p-4 rounded-xl transition-all duration-200"
                                            :class="studentSelected ? 'bg-blue-50 border-2 border-blue-400' : 'bg-gray-50 hover:bg-gray-100 border-2 border-transparent'">
                                        <div class="w-5 h-5 rounded-lg border-2 transition-colors duration-200 mr-3"
                                             :class="studentSelected ? 'bg-blue-600 border-blue-600' : 'border-gray-300 bg-white'">
                                            <svg class="w-full h-full text-white" viewBox="0 0 20 20" fill="currentColor" x-show="studentSelected">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </span>
                                            <span class="block text-xs text-gray-500 mt-0.5">
                                                ID: {{ $student->student_id }}
                                            </span>
                                        </div>
                                    </button>
                                    <input type="checkbox" 
                                           name="students[]" 
                                           value="{{ $student->id }}"
                                           x-model="studentSelected"
                                           class="hidden">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('classes.index') }}"
               class="px-6 py-2.5 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-700 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                        Update Class
                    </button>
                </div>
            </form>
</div>
@endsection

@push('scripts')
<script>
function searchStudents(query) {
    const studentCards = document.querySelectorAll('[name="students[]"]').forEach(checkbox => {
        const card = checkbox.closest('label');
        const studentName = card.textContent.toLowerCase();
        if (query === '' || studentName.includes(query.toLowerCase())) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endpush
