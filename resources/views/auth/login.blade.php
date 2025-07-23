{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    <div
        class="flex flex-col items-center min-h-screen pt-6 sm:justify-center sm:pt-0 bg-gradient-to-br from-dark-brown via-medium-brown to-light-brown">
        <div
            class="w-full px-8 py-8 mt-6 overflow-hidden border shadow-2xl sm:max-w-md bg-white/95 backdrop-blur-sm sm:rounded-2xl border-primary-orange/20">
            {{-- Logo Section --}}
            <div class="mb-8 text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="object-contain w-16 h-16">
                </div>
                <h1 class="mb-2 text-2xl font-bold font-poppins text-dark-brown">SEkoPInang</h1>
                <p class="text-sm font-medium text-medium-brown font-poppins">Harmonisasi Jelang SE: Data Kedai Kopi di
                    Tanjungpinang</p>
            </div>

            {{-- Validation Errors --}}
            <x-validation-errors class="mb-4" />

            {{-- Status Message --}}
            @if (session('status'))
                <div class="p-3 mb-4 text-sm font-medium text-green-600 border border-green-200 rounded-lg bg-green-50">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                {{-- Email Field --}}
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-dark-brown font-poppins">
                        Email
                    </label>
                    <input id="email"
                        class="w-full px-4 py-3 transition-all duration-200 border rounded-lg border-light-brown/30 focus:outline-none focus:ring-2 focus:ring-primary-orange focus:border-transparent bg-white/80 font-poppins text-dark-brown placeholder-medium-brown/60"
                        type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username" placeholder="Masukkan email Anda">
                </div>

                {{-- Password Field --}}
                <div class="mb-4">
                    <label for="password" class="block mb-2 text-sm font-medium text-dark-brown font-poppins">
                        Password
                    </label>
                    <input id="password"
                        class="w-full px-4 py-3 transition-all duration-200 border rounded-lg border-light-brown/30 focus:outline-none focus:ring-2 focus:ring-primary-orange focus:border-transparent bg-white/80 font-poppins text-dark-brown placeholder-medium-brown/60"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="Masukkan password Anda">
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center mb-6">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="w-4 h-4 transition-colors duration-200 rounded text-primary-orange focus:ring-primary-orange border-light-brown/30">
                    <label for="remember_me" class="ml-2 text-sm text-medium-brown font-poppins">
                        Ingat saya
                    </label>
                </div>

                {{-- Login Button --}}
                <button type="submit" id="loginBtn"
                    class="w-full px-6 py-3 bg-gradient-to-r from-primary-orange to-bright-orange text-white font-poppins font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-orange focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <span id="loginText">Masuk</span>
                    <span id="loadingText" class="hidden">
                        <svg class="inline w-4 h-4 mr-2 -ml-1 text-white animate-spin"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </form>

            {{-- Decorative Elements --}}
            <div class="pt-6 mt-8 border-t border-light-brown/20">
                <div class="flex justify-center space-x-2">
                    <div class="w-2 h-2 rounded-full bg-primary-orange animate-pulse"></div>
                    <div class="w-2 h-2 rounded-full bg-bright-orange animate-pulse" style="animation-delay: 0.2s">
                    </div>
                    <div class="w-2 h-2 rounded-full bg-cream-yellow animate-pulse" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Animation Script --}}
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Get form elements
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loadingText = document.getElementById('loadingText');

            // Basic form validation
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                return; // Let default validation handle this
            }

            // Show loading state
            loginBtn.disabled = true;
            loginText.classList.add('hidden');
            loadingText.classList.remove('hidden');

            // Optional: Add a minimum loading time for better UX
            setTimeout(() => {
                // Form will submit naturally after this
            }, 500);
        });

        // Handle validation errors - reset button if there are errors
        document.addEventListener('DOMContentLoaded', function() {
            const hasErrors = document.querySelector('.validation-errors') ||
                document.querySelector('[class*="error"]') ||
                '{{ $errors->any() ? 'true' : 'false' }}' === 'true';

            if (hasErrors) {
                const loginBtn = document.getElementById('loginBtn');
                const loginText = document.getElementById('loginText');
                const loadingText = document.getElementById('loadingText');

                loginBtn.disabled = false;
                loginText.classList.remove('hidden');
                loadingText.classList.add('hidden');
            }
        });
    </script>
</x-guest-layout>
