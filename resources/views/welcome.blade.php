<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%234f46e5' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .shape-blob {
            position: absolute;
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
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }

        /* Mobile Menu Styles */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(255, 255, 255, 0.98);
            z-index: 50;
            padding-top: 5rem;
            transition: all 0.3s ease-in-out;
        }
        .mobile-menu.active {
            display: block;
        }
        @media (max-width: 768px) {
            .desktop-menu {
                display: none;
            }
            .menu-button {
                display: block;
            }
            .hero-content {
                text-align: center;
                padding: 2rem 1rem;
            }
            .hero-buttons {
                justify-content: center;
            }
            .feature-card {
                margin-bottom: 2rem;
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .footer-content {
                text-align: center;
            }
            .social-links {
                justify-content: center;
            }
        }
    </style>
</head>
<body class="bg-gray-50 bg-pattern relative overflow-x-hidden">
    <!-- Decorative Shapes -->

    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-sm shadow-md fixed w-full top-0 z-50">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-indigo-600">
                        <i class="fas fa-school mr-2"></i>
                        Scholify
                    </span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="#home" class="px-4 py-2 text-indigo-600 hover:text-indigo-800">Home</a>
                    <a href="#about" class="px-4 py-2 text-indigo-600 hover:text-indigo-800">About</a>
                    <a href="#features" class="px-4 py-2 text-indigo-600 hover:text-indigo-800">Features</a>
                    <a href="#contact" class="px-4 py-2 text-indigo-600 hover:text-indigo-800">Contact</a>
                    <a href="{{route('login')}}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg hover:shadow-xl">
                        Login
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-indigo-600 focus:outline-none" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </nav>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu" class="mobile-menu">
        <div class="container mx-auto px-6">
            <div class="flex flex-col items-center space-y-6">
                <a href="#home" class="text-xl text-indigo-600 hover:text-indigo-800" onclick="toggleMobileMenu()">Home</a>
                <a href="#about" class="text-xl text-indigo-600 hover:text-indigo-800" onclick="toggleMobileMenu()">About</a>
                <a href="#features" class="text-xl text-indigo-600 hover:text-indigo-800" onclick="toggleMobileMenu()">Features</a>
                <a href="#contact" class="text-xl text-indigo-600 hover:text-indigo-800" onclick="toggleMobileMenu()">Contact</a>
                <a href="/login" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg hover:shadow-xl">
                    Login
                </a>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <main class="pt-24">
        <div id="home" class="container mx-auto px-6 py-16 relative">
            <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-1/2 right-1/4 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0 relative z-10">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
                        Welcome to Scholify
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        Empowering education through innovative management solutions. Streamline your school's operations with our comprehensive system.
                    </p>
                    <div class="space-x-4">
                        <a href="#features" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg hover:shadow-xl">
                            Learn More
                        </a>
                        <a href="#contact" class="border-2 border-indigo-600 text-indigo-600 px-8 py-3 rounded-lg hover:bg-indigo-50 transition duration-300">
                            Contact Us
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl opacity-10 blur-2xl"></div>
                    <div class="rounded-2xl p-8 relative z-10">
                        <img src="{{ asset('education_illustrations.png') }}" 
                             alt="Education Illustration" 
                             class="w-full h-auto rounded-2xl shadow-lg floating">
                    </div>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div id="about" class="bg-white/80 backdrop-blur-sm py-16 relative">
            <div class="container mx-auto px-6 relative z-10">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">About Us</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-2xl font-semibold mb-4">Empowering Education Through Technology</h3>
                        <p class="text-gray-600 mb-4">
                            At Scholify, we believe in transforming educational institutions through innovative management solutions. Our platform streamlines administrative tasks, enhances communication, and improves learning outcomes.
                        </p>
                        <p class="text-gray-600 mb-4">
                            With years of experience in education technology, we understand the unique challenges faced by schools and provide comprehensive solutions to address them.
                        </p>
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-indigo-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-indigo-600 mb-1">1000+</div>
                                <div class="text-gray-600">Students Served</div>
                            </div>
                            <div class="bg-indigo-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-indigo-600 mb-1">50+</div>
                                <div class="text-gray-600">Partner Schools</div>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl opacity-10 blur-2xl"></div>
                        <img src="https://img.freepik.com/free-vector/teaching-concept-illustration_114360-1708.jpg" 
                             alt="About Us Illustration" 
                             class="w-full h-auto rounded-2xl shadow-lg relative z-10">
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="bg-white/80 backdrop-blur-sm py-16 relative">
            <div class="absolute inset-0 bg-pattern opacity-5"></div>
            <div class="container mx-auto px-6 relative z-10">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Key Features</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                        <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mb-6 mx-auto floating">
                            <i class="fas fa-users text-5xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-center">Student Management</h3>
                        <p class="text-gray-600 text-center">Efficiently manage student records, attendance, and performance tracking.</p>
                    </div>
                    <div class="p-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                        <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mb-6 mx-auto floating">
                            <i class="fas fa-chalkboard-teacher text-5xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-center">Teacher Portal</h3>
                        <p class="text-gray-600 text-center">Dedicated portal for teachers to manage classes and student progress.</p>
                    </div>
                    <div class="p-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                        <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mb-6 mx-auto floating">
                            <i class="fas fa-chart-line text-5xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-center">Performance Analytics</h3>
                        <p class="text-gray-600 text-center">Comprehensive analytics and reporting for informed decision-making.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Educational Process Section -->
        <div class="py-16 bg-gradient-to-b from-white to-indigo-50">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Our Educational Process</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mb-6 mx-auto floating">
                            <i class="fas fa-book-reader text-5xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Interactive Learning</h3>
                        <p class="text-gray-600">Engaging educational content</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mb-6 mx-auto floating">
                            <i class="fas fa-user-friends text-5xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Collaboration</h3>
                        <p class="text-gray-600">Work together effectively</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mb-6 mx-auto floating">
                            <i class="fas fa-award text-5xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Achievement</h3>
                        <p class="text-gray-600">Reach your goals</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mb-6 mx-auto floating">
                            <i class="fas fa-graduation-cap text-5xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Graduation</h3>
                        <p class="text-gray-600">Celebrate success</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="py-16 bg-gradient-to-r from-indigo-500 to-purple-600">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-white text-center">
                    <div class="p-4">
                        <div class="text-4xl font-bold mb-2">1000+</div>
                        <div class="text-indigo-100">Students</div>
                    </div>
                    <div class="p-4">
                        <div class="text-4xl font-bold mb-2">50+</div>
                        <div class="text-indigo-100">Teachers</div>
                    </div>
                    <div class="p-4">
                        <div class="text-4xl font-bold mb-2">100%</div>
                        <div class="text-indigo-100">Satisfaction</div>
                    </div>
                    <div class="p-4">
                        <div class="text-4xl font-bold mb-2">24/7</div>
                        <div class="text-indigo-100">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
       
    <!-- Contact Section -->
    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white mt-auto">
        <!-- Footer Top Section -->
        <div class="border-b border-gray-800">
            <div class="container mx-auto px-6 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- About Section -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-school text-2xl text-indigo-500"></i>
                            <span class="text-2xl font-bold">Scholify</span>
                        </div>
                        <p class="text-gray-400 text-sm">
                            Empowering education through innovative management solutions. Join us in shaping the future of education.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-300">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-300">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-300">
                                <i class="fab fa-linkedin-in text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-300">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-300">About Us</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-300">Our Services</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-300">Privacy Policy</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-300">Terms of Service</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center space-x-3">
                                <i class="fas fa-map-marker-alt text-indigo-500"></i>
                                <span class="text-gray-400">123 Education St, School District</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <i class="fas fa-phone text-indigo-500"></i>
                                <span class="text-gray-400">+1 234 567 8900</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-indigo-500"></i>
                                <span class="text-gray-400">info@Scholify.com</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                        <p class="text-gray-400 text-sm mb-4">Subscribe to our newsletter for updates and news.</p>
                        <form class="space-y-3">
                            <div class="relative">
                                <input type="email" 
                                       placeholder="Enter your email" 
                                       class="w-full px-4 py-2 bg-gray-800 text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-300">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="container mx-auto px-6 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-400 text-sm">
                    2024 Scholify. All rights reserved.
                </div>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-indigo-500 text-sm transition-colors duration-300">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-indigo-500 text-sm transition-colors duration-300">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-indigo-500 text-sm transition-colors duration-300">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="fixed bottom-8 right-8 bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-700 transition-colors duration-300 z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('active');
            document.body.classList.toggle('overflow-hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuButton = document.querySelector('.menu-button');
            if (mobileMenu.classList.contains('active') && 
                !mobileMenu.contains(event.target) && 
                !menuButton.contains(event.target)) {
                toggleMobileMenu();
            }
        });

        // Close mobile menu when resizing to desktop view
        window.addEventListener('resize', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            if (window.innerWidth >= 768 && mobileMenu.classList.contains('active')) {
                toggleMobileMenu();
            }
        });
    </script>
</body>
</html>
