@extends('layouts.admin-dashboard')

@section('content')
<div x-data="scheduleForm()" class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 mb-6">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800">Create Schedule</h1>
            <p class="text-gray-600 mt-2">Create a new weekly class schedule</p>
        </div>
    </div>

    <!-- Create Schedule Form -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <form action="{{ route('schedules.store') }}" method="POST" class="p-6" @submit.prevent="submitForm">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Class Selection -->
            <div class="mb-6">
                <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                <select name="class_id" id="class_id" required x-model="selectedClass"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select a class</option>
                    @foreach($availableClasses as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Weekly Schedule -->
            <div x-show="selectedClass" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $day }}</h3>
                            <button type="button" @click="addTimeSlot('{{ $day }}')"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100">
                                <i class="fas fa-plus mr-1"></i> Add Time Slot
                            </button>
                        </div>

                        <template x-for="(slot, index) in slots['{{ $day }}']" :key="index">
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Time Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                                        <div class="flex items-center gap-2">
                                            <select x-model="slot.start_time" :name="`schedule[{{ $day }}][${index}][start_time]`" required
                                                class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach(range(8, 17) as $hour)
                                                    <option value="{{ sprintf('%02d:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                                                @endforeach
                                            </select>
                                            <span>to</span>
                                            <select x-model="slot.end_time" :name="`schedule[{{ $day }}][${index}][end_time]`" required
                                                class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach(range(9, 18) as $hour)
                                                    <option value="{{ sprintf('%02d:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Teacher Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Teacher</label>
                                        <select x-model="slot.teacher_id" :name="`schedule[{{ $day }}][${index}][teacher_id]`" required
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Select Teacher</option>
                                            <template x-for="teacher in getTeachersForClass()" :key="teacher.teacher_id">
                                                <option :value="teacher.teacher_id" x-text="teacher.teacher_name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Module Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Module</label>
                                        <select x-model="slot.module_id" :name="`schedule[{{ $day }}][${index}][module_id]`" required
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                            :disabled="!slot.teacher_id">
                                            <option value="">Select Module</option>
                                            <template x-for="subject in getTeacherSubjects(slot.teacher_id)" :key="subject.module_id">
                                                <option :value="subject.module_id" x-text="subject.module_name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Room Input -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Room</label>
                                        <input type="text" x-model="slot.room" :name="`schedule[{{ $day }}][${index}][room]`"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                            required placeholder="Enter room number">
                                    </div>
                                </div>

                                <div class="flex justify-end mt-4">
                                    <button type="button" @click="removeTimeSlot('{{ $day }}', index)"
                                        class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash mr-1"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                @endforeach
            </div>

            <div x-show="!selectedClass" class="text-center py-8 text-gray-500">
                Please select a class to create a schedule
            </div>

            <!-- Error Message -->
            <div class="text-red-500 text-sm mt-4" x-show="formErrors">
                Please fill in all required fields
            </div>

            <!-- Form Actions -->
            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('schedules.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        x-show="selectedClass"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    Create Schedule
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function scheduleForm() {
    return {
        selectedClass: '',
        slots: {
            'Monday': [],
            'Tuesday': [],
            'Wednesday': [],
            'Thursday': [],
            'Friday': [],
            'Saturday': []
        },
        formErrors: false,
        teacherClassSubjects: {},
        
        async init() {
            this.$watch('selectedClass', async (value) => {
                if (value) {
                    const response = await fetch(`/schedules/class/${value}/teachers`);
                    const data = await response.json();
                    this.teacherClassSubjects[value] = data;
                }
            });
        },
        
        addTimeSlot(day) {
            this.slots[day].push({
                start_time: '08:00',
                end_time: '09:00',
                teacher_id: '',
                module_id: '',
                room: ''
            });
        },
        
        removeTimeSlot(day, index) {
            this.slots[day].splice(index, 1);
        },
        
        getTeachersForClass() {
            if (!this.selectedClass || !this.teacherClassSubjects[this.selectedClass]) {
                return [];
            }
            
            // Get unique teachers
            const teachers = {};
            this.teacherClassSubjects[this.selectedClass].forEach(subject => {
                teachers[subject.teacher_id] = {
                    teacher_id: subject.teacher_id,
                    teacher_name: subject.teacher_name
                };
            });
            
            return Object.values(teachers);
        },
        
        getTeacherSubjects(teacherId) {
            if (!this.selectedClass || !teacherId) return [];
            
            return this.teacherClassSubjects[this.selectedClass].filter(subject => 
                subject.teacher_id.toString() === teacherId.toString()
            );
        },
        
        submitForm() {
            this.formErrors = false;
            
            if (!this.selectedClass) {
                this.formErrors = true;
                return;
            }
            
            // Check if at least one slot is added
            const hasSlots = Object.values(this.slots).some(daySlots => daySlots.length > 0);
            if (!hasSlots) {
                this.formErrors = true;
                return;
            }
            
            // Check if all slots are filled
            for (const day in this.slots) {
                for (const slot of this.slots[day]) {
                    if (!slot.start_time || !slot.end_time || !slot.teacher_id || !slot.module_id || !slot.room) {
                        this.formErrors = true;
                        return;
                    }
                }
            }
            
            this.$el.submit();
        }
    }
}
</script>
@endpush
@endsection
