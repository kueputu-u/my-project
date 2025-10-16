<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Air Hotel - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-2xl font-bold">Air Hotel</a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('home') }}" class="hover:text-blue-200">Home</a>
                        <a href="{{ route('rooms.index') }}" class="hover:text-blue-200">Rooms</a>
                        @auth
                            <a href="{{ route('reservations.index') }}" class="hover:text-blue-200">My Reservations</a>
                        @endauth
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-200">
                                <i class="fas fa-cog mr-1"></i>Admin Panel
                            </a>
                        @endif
                        <span>Welcome, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-blue-200">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-blue-200">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-400">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col">
        <div class="flex-grow">
            @yield('content')
        </div>
    </main>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 Air Hotel. All rights reserved.</p>
            <p class="mt-2">Luxury accommodation with breathtaking views</p>
        </div>
    </footer>

    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.fixed');
            flashMessages.forEach(msg => {
                msg.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>