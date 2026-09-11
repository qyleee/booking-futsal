<x-auth.layout :title="'Login'" :subtitle="'Masuk ke akun Anda'">

    {{-- Error Message --}}
    @if ($errors->any())
        <div class="auth-error mb-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="auth-label">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                class="auth-input"
                placeholder="email@contoh.com"
                required
                autofocus
            >
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="auth-label">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                class="auth-input"
                placeholder="Masukkan password"
                required
            >
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        {{-- Tombol Login --}}
        <button type="submit" class="auth-btn">
            Masuk
        </button>
    </form>

    {{-- Link Register --}}
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">
            Belum punya akun?
            <a href="/register" class="auth-link">Daftar sekarang</a>
        </p>
    </div>

</x-auth.layout>
