<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white mb-1">Selamat Datang</h2>
        <p class="text-emerald-200/70 text-sm">Silakan masuk ke akun Anda untuk melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username Address -->
        <div>
            <label for="username" class="block text-sm font-medium text-emerald-100 mb-1.5">{{ __('Username') }}</label>
            <input id="username" class="block w-full bg-emerald-950/50 border border-emerald-500/30 text-white rounded-xl focus:ring-emerald-400 focus:border-emerald-400 placeholder-emerald-500/50 px-4 py-3 transition-colors" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="Masukkan username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <label for="password" class="block text-sm font-medium text-emerald-100 mb-1.5">{{ __('Password') }}</label>
            <input id="password" class="block w-full bg-emerald-950/50 border border-emerald-500/30 text-white rounded-xl focus:ring-emerald-400 focus:border-emerald-400 placeholder-emerald-500/50 px-4 py-3 transition-colors" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-5">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-emerald-500/30 bg-emerald-950/50 text-emerald-500 shadow-sm focus:ring-emerald-400 focus:ring-offset-emerald-900 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-emerald-200/70 group-hover:text-emerald-200 transition-colors">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_0_15px_rgba(52,211,153,0.3)] text-sm font-bold text-emerald-950 bg-emerald-400 hover:bg-emerald-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400 focus:ring-offset-emerald-900 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                {{ __('Masuk Sekarang') }}
            </button>
        </div>
    </form>
</x-guest-layout>
