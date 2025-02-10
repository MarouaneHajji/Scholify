@extends('layouts.admin-dashboard')

@section('content')
<div class="w-full" x-data="{ showTeachers: false, showStudents: false, selectedTeacher: null }">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 mb-6">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800">Create New Class</h1>
        </div>
    </div>

    <!-- Create Class Form -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('classes.store') }}" method="POST" id="createClassForm">
            @csrf
            
            <!-- Class Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700">Class Name</label>
                <input type="text" name="name" id="name" 
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 @error('name') border-red-500 @enderror"
                    value="{{ old('name') }}"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Branch Selection -->
            <div class="mb-6">
                <label for="branch_id" class="block text-sm font-semibold text-gray-700">Branch</label>
                <select name="branch_id" id="branch_id" 
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600"
                    required>
                    <option value="">Select Branch</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Teachers and Modules -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Teachers & Modules</h2>
                        <button type="button" 
                                @click="showTeachers = !showTeachers"
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                            <span x-text="showTeachers ? 'Hide Teachers' : 'Show Teachers'"></span>
                        </button>
                    </div>

                    @foreach($branches as $branch)
                        <div class="branch-modules" id="branch-{{ $branch->id }}" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($branch->modules as $module)
                                    <div class="bg-gray-50 rounded-xl p-4" x-data="{ selectedTeacher: '' }">
                                        <div class="flex flex-col space-y-3">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $module->name }}</h4>
                                                    <p class="text-sm text-gray-500">Factor: {{ $module->factor }}</p>
                                                </div>
                                            </div>
                                            <button type="button"
                                                    @click="selectedTeacher = selectedTeacher === '' ? 'module_{{ $module->id }}' : ''"
                                                    class="w-full px-3 py-2 text-sm text-blue-600 hover:text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-50 transition-all duration-200">
                                                <span x-text="selectedTeacher === 'module_{{ $module->id }}' ? 'Hide Teachers' : 'Assign Teacher'"></span>
                                            </button>
                                            <div x-show="selectedTeacher === 'module_{{ $module->id }}'"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 transform scale-95"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 class="mt-2">
                                                @foreach($teachers as $teacher)
                                                    <div class="relative" x-data="{ isSelected: false }">
                                                        <button type="button"
                                                                @click="isSelected = !isSelected"
                                                                class="w-full text-left p-2 rounded-lg transition-all duration-200 mb-2"
                                                                :class="isSelected ? 'bg-blue-50 border-2 border-blue-400' : 'hover:bg-gray-100 border-2 border-transparent'">
                                                            <div class="flex items-center">
                                                                <div class="w-4 h-4 rounded border-2 transition-colors duration-200 mr-2"
                                                                     :class="isSelected ? 'bg-blue-600 border-blue-600' : 'border-gray-300 bg-white'">
                                                                    <svg class="w-full h-full text-white" viewBox="0 0 20 20" fill="currentColor" x-show="isSelected">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                </div>
                                                                <span class="text-sm">{{ $teacher->first_name }} {{ $teacher->last_name }}</span>
                                                            </div>
                                                        </button>
                                                        <input type="radio" 
                                                               name="module_teachers[{{ $module->id }}]" 
                                                               value="{{ $teacher->id }}"
                                                               x-model="isSelected"
                                                               class="hidden">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Students -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
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
                                <div x-data="{ studentSelected: false }">
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

            <!-- Form Actions -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('classes.index') }}" 
                    class="px-6 py-2.5 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-700 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200"
                    id="submitButton">
                    Create Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('branch_id').addEventListener('change', function() {
    // Hide all branch modules first
    document.querySelectorAll('.branch-modules').forEach(div => {
        div.style.display = 'none';
    });

    // Show the selected branch's modules
    const selectedBranchId = this.value;
    if (selectedBranchId) {
        const moduleDiv = document.getElementById(`branch-${selectedBranchId}`);
        if (moduleDiv) {
            moduleDiv.style.display = 'block';
        }
    }
});

function searchStudents(query) {
    document.querySelectorAll('[name="students[]"]').forEach(checkbox => {
        const card = checkbox.closest('div[x-data]');
        const studentName = card.textContent.toLowerCase();
        if (query === '' || studentName.includes(query.toLowerCase())) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

document.getElementById('createClassForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    console.log('Form submission started...');
    
    const submitButton = document.getElementById('submitButton');
    submitButton.disabled = true;
    submitButton.innerHTML = 'Creating...';
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            throw new Error(data.message || 'Failed to create class');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        submitButton.disabled = false;
        submitButton.innerHTML = 'Create Class';
        alert(error.message || 'Failed to create class. Please try again.');
    });
});
</script>
@endpush
