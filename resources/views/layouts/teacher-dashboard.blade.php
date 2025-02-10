@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Dashboard') - Scholify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%234f46e5' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hidden {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-50 bg-pattern min-h-screen">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0">
        <div class="h-full px-3 py-4 overflow-y-auto bg-white/80 backdrop-blur-sm border-r border-gray-200">
            <div class="flex items-center mb-5 p-2">
                <i class="fas fa-school text-2xl text-indigo-600 mr-2"></i>
                <span class="text-xl font-semibold text-gray-800">Scholify</span>
            </div>
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('dashboard.teacher') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-indigo-50 group transition-colors duration-200 {{ request()->routeIs('dashboard.teacher') ? 'bg-indigo-50' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 text-indigo-500 transition duration-75"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.classes') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-indigo-50 group transition-colors duration-200 {{ request()->routeIs('teacher.classes') ? 'bg-indigo-50' : '' }}">
                        <i class="fas fa-chalkboard w-5 h-5 text-indigo-500 transition duration-75"></i>
                        <span class="ml-3">My Classes</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-indigo-50 group transition-colors duration-200">
                        <i class="fas fa-book w-5 h-5 text-indigo-500 transition duration-75"></i>
                        <span class="ml-3">My Subjects</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-indigo-50 group transition-colors duration-200">
                        <i class="fas fa-tasks w-5 h-5 text-indigo-500 transition duration-75"></i>
                        <span class="ml-3">Assignments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher-section.grades.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-indigo-50 group transition-colors duration-200 {{ request()->routeIs('teacher-section.grades.*') ? 'bg-indigo-50' : '' }}">
                        <i class="fas fa-star w-5 h-5 text-indigo-500 transition duration-75"></i>
                        <span class="ml-3">Grades</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-indigo-50 group transition-colors duration-200">
                        <i class="fas fa-bell w-5 h-5 text-indigo-500 transition duration-75"></i>
                        <span class="ml-3">Notifications</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-indigo-50 group transition-colors duration-200">
                        <i class="fas fa-envelope w-5 h-5 text-indigo-500 transition duration-75"></i>
                        <span class="ml-3">Messages</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-indigo-50 group transition-colors duration-200">
                        <i class="fas fa-cog w-5 h-5 text-indigo-500 transition duration-75"></i>
                        <span class="ml-3">Settings</span>
                    </a>
                </li>
            </ul>
            
            <!-- Logout Button -->
            <div class="mt-auto pt-4 border-t border-gray-200">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center p-2 w-full text-gray-900 rounded-lg hover:bg-red-50 group transition-colors duration-200">
                        <i class="fas fa-sign-out-alt w-5 h-5 text-red-500 transition duration-75"></i>
                        <span class="ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-64">
        <!-- Top Navigation -->
        <nav class="bg-white/80 backdrop-blur-sm border border-gray-200 px-4 py-2.5 rounded-lg mb-4 flex justify-between items-center">
            <div class="flex items-center">
                <button class="sm:hidden mr-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-gray-600"></i>
                </button>
                <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Teacher Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4">
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-bell text-xl"></i>
                </button>
                <div class="flex items-center">
                    <a href="{{ route('teacher.profile') }}" class="cursor-pointer">
                        <img class="w-8 h-8 rounded-full hover:ring-2 hover:ring-indigo-500 transition-all duration-200" 
                             src="{{ auth()->user()->profile_picture ? Storage::url(auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->teacher->first_name . ' ' . auth()->user()->teacher->last_name) . '&background=6366f1&color=fff' }}" 
                             alt="{{ auth()->user()->teacher->first_name }}">
                    </a>
                </div>
            </div>
        </nav>

        <div class="p-4">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
</body>
</html>