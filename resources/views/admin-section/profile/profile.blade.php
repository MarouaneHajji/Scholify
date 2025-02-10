@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white/80 backdrop-blur-sm shadow-md rounded-lg overflow-hidden">
        <!-- Profile Header with Background -->
        <div class="relative h-48 bg-gradient-to-r from-indigo-500 to-purple-600">
            <div class="absolute bottom-0 left-0 right-0 px-6 py-4 bg-black/30 backdrop-blur-sm">
                <div class="flex items-center space-x-4">
                    <!-- Profile Picture -->
                    <div class="relative group">
                        <img class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover" 
                             src="{{ auth()->user()->profile_picture ? Storage::url(auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff' }}" 
                             alt="Profile Picture">
                        <label for="profile_picture" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity duration-200">
                            <i class="fas fa-camera text-white text-xl"></i>
                        </label>
                        <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*">
                    </div>
                    <!-- User Info -->
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">{{ auth()->user()->name }}</h1>
                        <p class="text-white/80">{{ auth()->user()->email }}</p>
                        <span class="inline-block mt-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="p-6" x-data="{ isEditing: false }">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- View Mode -->
            <div x-show="!isEditing" class="space-y-8">
                <!-- Personal Information Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Personal Information</h3>
                        <button @click="isEditing = true" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center space-x-2">
                            <i class="fas fa-edit"></i>
                            <span>Edit Profile</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Full Name</p>
                            <p class="mt-1 text-lg text-gray-900">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email Address</p>
                            <p class="mt-1 text-lg text-gray-900">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Account Security Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Security</h3>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Password</p>
                        <p class="mt-1 text-lg text-gray-900">••••••••</p>
                    </div>
                </div>
            </div>

            <!-- Edit Mode -->
            <div x-show="isEditing" x-cloak>
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Personal Information Section -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Edit Personal Information</h3>
                            <button type="button" @click="isEditing = false" 
                                    class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200">
                                Cancel
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" 
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" 
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="current_password" class="text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" name="current_password" id="current_password" 
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>

                            <div class="space-y-2">
                                <label for="new_password" class="text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" name="new_password" id="new_password" 
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label for="new_password_confirmation" class="text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 flex items-center space-x-2">
                            <i class="fas fa-save"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_picture').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const formData = new FormData();
        formData.append('profile_picture', e.target.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("profile.update.picture") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
</script>
@endsection 