<x-auth.layout :title="'Register'" :subtitle="'Buat akun baru'">

    @if ($errors->any())
        <div class="auth-error">
            <ul style="list-style:none;padding:0;margin:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="auth-group">
            <label for="name" class="auth-label">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="auth-input" placeholder="Masukkan nama" required autofocus>
        </div>

        <div class="auth-group">
            <label for="email" class="auth-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="auth-input" placeholder="email@contoh.com" required>
        </div>

        <div class="auth-group">
            <label for="password" class="auth-label">Password</label>
            <input type="password" id="password" name="password" class="auth-input" placeholder="Minimal 8 karakter" required>
        </div>

        <div class="auth-group">
            <label for="password_confirmation" class="auth-label">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="auth-input" placeholder="Ulangi password" required>
        </div>

        <button type="submit" class="auth-btn">Daftar</button>
    </form>

    <div class="auth-footer">
        Sudah punya akun? <a href="/login">Masuk</a>
    </div>

</x-auth.layout>
