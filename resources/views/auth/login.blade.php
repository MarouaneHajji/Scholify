<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Scholify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%234f46e5' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .shape-blob {
            background: linear-gradient(45deg, rgba(79, 70, 229, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: blob-movement 25s infinite ease-in-out;
        }
        @keyframes blob-movement {
            0% { transform: translate(0%, 0%) rotate(0deg); }
            25% { transform: translate(5%, 5%) rotate(90deg); }
            50% { transform: translate(0%, 10%) rotate(180deg); }
            75% { transform: translate(-5%, 5%) rotate(270deg); }
            100% { transform: translate(0%, 0%) rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50 bg-pattern min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
    <a href="/" class="absolute top-4 left-4 flex items-center text-gray-600 hover:text-indigo-600 transition-colors duration-200">
        <i class="fas fa-arrow-left mr-2"></i>
        <span class="font-medium">Back to Home</span>
    </a>
    <!-- Decorative blobs -->
    <div class="absolute top-0 left-0 w-72 h-72 shape-blob opacity-70 -z-10"></div>
    <div class="absolute bottom-0 right-0 w-72 h-72 shape-blob opacity-70 -z-10"></div>

    <div class="max-w-md w-full space-y-8 bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-xl relative z-10">
        <div>
            <h1 class="text-center text-3xl font-bold text-indigo-600 mb-2">
                <i class="fas fa-school mr-2"></i>
                Scholify
            </h1>
            <h2 class="mt-6 text-center text-2xl font-semibold text-gray-800">
                Sign in to your account
            </h2>
        </div>
        <form id="loginForm" class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 group-hover:text-indigo-500 transition-colors duration-200"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                               class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-2.5 border-2 border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent hover:border-indigo-300 transition-all duration-200 bg-white/50 backdrop-blur-sm"
                               placeholder="Enter your email">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <i class="fas fa-at text-indigo-400"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 group-hover:text-indigo-500 transition-colors duration-200"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                               class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-2.5 border-2 border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent hover:border-indigo-300 transition-all duration-200 bg-white/50 backdrop-blur-sm"
                               placeholder="Enter your password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <i class="fas fa-key text-indigo-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700 cursor-pointer hover:text-indigo-500 transition-colors duration-200">
                        Remember me
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-indigo-500 group-hover:text-indigo-400"></i>
                    </span>
                    Sign in
                </button>
            </div>
        </form>
    </div>

    <script>
        // Check for Laravel errors and show them in SweetAlert2
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#4F46E5',
                confirmButtonText: 'Try Again'
            });
        @endif

        // Show success message if exists
        @if(session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('status') }}',
                confirmButtonColor: '#4F46E5'
            });
        @endif

        // Form validation with SweetAlert2
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields',
                    confirmButtonColor: '#4F46E5',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
</body>
</html>
