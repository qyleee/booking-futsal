<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * FormRequest LoginRequest - Menangani proses validasi dan autentikasi login.
 * Memvalidasi email dan password, melakukan rate limiting untuk mencegah brute-force,
 * serta mengautentikasi kredensial user.
 */
class LoginRequest extends FormRequest
{
    /**
     * Menentukan apakah user diizinkan melakukan request ini.
     * Selalu mengembalikan true karena login bisa diakses semua orang.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendapatkan aturan validasi yang berlaku untuk request login.
     * - email: wajib, berupa string, dan format email valid.
     * - password: wajib, berupa string.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Melakukan autentikasi kredensial login.
     * Memeriksa rate limiting terlebih dahulu, lalu mencoba login dengan email dan password.
     * Jika gagal, menambah hit rate limiter dan melempar exception validasi.
     * Jika berhasil, membersihkan hit rate limiter.
     *
     * @throws ValidationException Jika login gagal atau terkena rate limit.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Memastikan request login tidak terkena rate limiting.
     * Maksimal 5 percobaan login sebelum dikunci sementara.
     * Jika terkena rate limit, melempar exception dengan pesan waktu tunggu.
     *
     * @throws ValidationException Jika melebihi batas percobaan login.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Mendapatkan key unik untuk rate limiting.
     * Digabungkan dari email (lowercase) dan IP address user.
     *
     * @return string Key rate limiting.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
