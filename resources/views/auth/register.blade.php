<x-auth.layout :title="'Register'" :subtitle="'Buat akun baru'">

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

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        {{-- Nama --}}
        <div class="mb-4">
            <label for="name" class="auth-label">Nama Lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                class="auth-input"
                placeholder="Masukkan nama lengkap"
                required
                autofocus
            >
        </div>

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
                placeholder="Minimal 8 karakter"
                required
            >
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-6">
            <label for="password_confirmation" class="auth-label">Konfirmasi Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="auth-input"
                placeholder="Ulangi password"
                required
            >
        </div>

        {{-- Tombol Register --}}
        <button type="submit" class="auth-btn">
            Daftar
        </button>
    </form>

    {{-- Link Login --}}
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">
            Sudah punya akun?
            <a href="/login" class="auth-link">Masuk</a>
        </p>
    </div>

</x-auth.layout>
