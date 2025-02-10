@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Form Container with Back Button -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Back Button in Form Header -->
            <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Edit Admin</h2>
                <a href="{{ route('admin.management') }}" 
                   class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="ml-2">Back</span>
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.update', $admin->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT') <!-- Specify PUT for updating -->

                <!-- Profile Icon Upload -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="profile_picture">
                        Profile Icon
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="relative group">
                            <div class="h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300 hover:border-blue-500 transition-colors duration-200">
                                @if ($admin->profile_picture)
                                    <img id="preview" src="{{ asset('storage/' . $admin->profile_picture) }}" alt="Current Picture" class="h-full w-full rounded-full object-cover">
                                @else
                                    <span id="placeholder" class="text-gray-400 group-hover:text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            <input type="file" 
                                   id="profile_picture" 
                                   name="profile_picture" 
                                   accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   onchange="previewImage(this)">
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>Click to upload or drag and drop</p>
                            <p>SVG, PNG, JPG (max. 2MB)</p>
                        </div>
                    </div>
                    @error('profile_picture')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Name
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $admin->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $admin->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                        Update Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const placeholder = document.getElementById('placeholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
