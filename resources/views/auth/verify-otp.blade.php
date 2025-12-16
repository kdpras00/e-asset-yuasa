<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - E-Asset Yuasa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .otp-input { text-align: center; }
        /* Hide arrows in number input */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body class="bg-[#F8F9FC] font-sans antialiased flex items-center justify-center min-h-screen relative overflow-hidden">

     <!-- Background Decor -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 left-0 w-full h-96 bg-[#0A1A32] rounded-b-[3rem] shadow-2xl"></div>
    </div>

    <div class="z-10 w-full max-w-md p-6">
        <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100">
            <!-- Header -->
            <div class="bg-gray-50/50 p-8 text-center border-b border-gray-100">
                 <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-full mb-4 text-[#0A1A32]">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Verify Identity</h2>
                <p class="text-gray-500 text-sm mt-1">Sent OTP to your registered email</p>
                
                 @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-xl mt-4 text-xs flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif
                 @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-xl mt-4 text-xs flex items-center justify-center">
                        <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Form -->
            <div class="p-8">
                <form action="{{ route('otp.check') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="flex justify-center gap-2 md:gap-3">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text" name="otp[]" maxlength="1" 
                            class="w-12 h-14 border-2 border-gray-200 rounded-xl otp-input focus:border-[#0A1A32] focus:ring-4 focus:ring-[#0A1A32]/10 focus:outline-none text-2xl font-bold text-[#0A1A32] bg-white transition-all shadow-sm"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 1 && this.nextElementSibling) this.nextElementSibling.focus();"
                            onkeydown="if(event.key === 'Backspace' && this.value.length === 0 && this.previousElementSibling) this.previousElementSibling.focus();"
                            required>
                        @endfor
                    </div>

                    <div class="text-center">
                         <p class="text-sm text-gray-500">Didn't receive the code?</p>
                         <a href="#" class="text-sm text-[#0A1A32] font-bold hover:underline mt-1 inline-block">Resend OTP</a>
                    </div>

                    <button type="submit" class="w-full bg-[#0A1A32] text-white font-bold py-3.5 rounded-xl shadow-lg hover:bg-[#152a4d] hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        Verify Code
                    </button>
                </form>
            </div>
            
             <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-100">
                <p class="text-gray-500 text-sm"><a href="{{ route('login') }}" class="text-gray-600 font-bold hover:text-[#0A1A32] flex items-center justify-center transition-colors"><i class="fas fa-arrow-left mr-2"></i> Back to Login</a></p>
            </div>
        </div>
        
         <p class="text-center text-gray-400 text-xs mt-6">&copy; {{ date('Y') }} PT. Yuasa Battery Indonesia</p>
    </div>
</body>
</html>
