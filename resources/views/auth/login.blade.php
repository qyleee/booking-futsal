<x-auth.layout :title="'Login'" :subtitle="'Masuk ke akun Anda'">

    @if ($errors->any())
        <div class="auth-error">
            <ul style="list-style:none;padding:0;margin:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="auth-group">
            <label for="email" class="auth-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="auth-input" placeholder="email@contoh.com" required autofocus>
        </div>

        <div class="auth-group">
            <label for="password" class="auth-label">Password</label>
            <input type="password" id="password" name="password" class="auth-input" placeholder="Masukkan password" required>
        </div>

        <div class="auth-row">
            <label class="auth-checkbox">
                <input type="checkbox" name="remember">
                Ingat saya
            </label>
        </div>

        <button type="submit" class="auth-btn">Masuk</button>
    </form>

    <div class="auth-footer">
        Belum punya akun? <a href="/register">Daftar</a>
    </div>

</x-auth.layout>
