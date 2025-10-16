<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Air Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center">Register for Air Hotel</h2>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" 
                           required autofocus>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" 
                           required>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" 
                           required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" 
                           required>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" 
                           required>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                    Register
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">
                    Already have an account? Login here
                </a>
            </div>
        </div>
    </div>
</body>
</html>