<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login' }} - FutsalBook</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Figtree', sans-serif; }
        body {
            background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -30%;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        body::after {
            content: "";
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.1);
            padding: 2.5rem;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .auth-logo {
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }
        .auth-logo:hover { transform: scale(1.05); }
        .auth-input {
            border-radius: 0.75rem;
            border: 1.5px solid #e5e7eb;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #f9fafb;
            width: 100%;
        }
        .auth-input:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
            background: #fff;
        }
        .auth-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            margin-bottom: 0.375rem;
            display: block;
        }
        .auth-btn {
            background: linear-gradient(135deg, #059669, #047857);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            font-size: 0.95rem;
            color: white;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .auth-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }
        .auth-btn:hover::before { left: 100%; }
        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.45);
        }
        .auth-link {
            color: #059669;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        .auth-link:hover {
            color: #047857;
            text-decoration: underline;
        }
        .auth-error {
            border-radius: 0.75rem;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #991b1b;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
        .auth-back {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            z-index: 10;
            color: rgba(255,255,255,0.8);
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .auth-back:hover { color: white; }
    </style>
</head>
<body class="antialiased">
    <a href="/" class="auth-back">
        <svg style="display:inline;vertical-align:middle;margin-right:4px;width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            {{-- Logo & Judul --}}
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-4 auth-logo">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900" style="letter-spacing:-0.025em;">Futsal<span class="text-emerald-600">Book</span></h1>
                <p class="text-gray-500 text-sm mt-1">{{ $subtitle ?? 'Masuk ke akun Anda' }}</p>
            </div>

            {{-- Card Form --}}
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
