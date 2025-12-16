<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Asset Yuasa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FC] font-sans antialiased flex items-center justify-center min-h-screen relative overflow-hidden">
    
    <!-- Background Decor -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 left-0 w-full h-96 bg-[#0A1A32] rounded-b-[3rem] shadow-2xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#9E3E3E]/5 rounded-full blur-3xl"></div>
    </div>

    <div class="z-10 w-full max-w-md p-6">
        <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100">
            <!-- Header -->
            <div class="bg-gray-50/50 p-8 text-center border-b border-gray-100">
                 <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-md mb-4">
                    <img src="{{ asset('storage/logo.jpeg') }}" alt="Yuasa Logo" class="h-10">
                </div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Create Account</h2>
                <p class="text-gray-500 text-sm mt-1">Join the asset management system</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Username</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" name="username" value="{{ old('username') }}" required 
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1A32]/20 focus:border-[#0A1A32] transition-colors text-gray-700 bg-gray-50/50 focus:bg-white"
                                placeholder="Choose a username">
                        </div>
                        @error('username')
                            <p class="text-red-500 text-xs mt-1 ml-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1A32]/20 focus:border-[#0A1A32] transition-colors text-gray-700 bg-gray-50/50 focus:bg-white"
                                placeholder="name@example.com">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1 ml-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="password" name="password" required 
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1A32]/20 focus:border-[#0A1A32] transition-colors text-gray-700 bg-gray-50/50 focus:bg-white"
                                placeholder="Create a strong password">
                        </div>
                        @error('password')
                             <p class="text-red-500 text-xs mt-1 ml-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-[#0A1A32] text-white font-bold py-3.5 rounded-xl shadow-lg hover:bg-[#152a4d] hover:shadow-xl transition-all transform hover:-translate-y-0.5 mt-2">
                        Create Account
                    </button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-100">
                <p class="text-gray-500 text-sm">Already have an account? <a href="{{ route('login') }}" class="text-[#0A1A32] font-bold hover:underline">Sign In</a></p>
            </div>
        </div>
        
        <p class="text-center text-gray-400 text-xs mt-6">&copy; {{ date('Y') }} PT. Yuasa Battery Indonesia</p>
    </div>
</body>
</html>
