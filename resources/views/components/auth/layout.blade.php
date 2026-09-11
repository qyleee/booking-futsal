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
        * { font-family: 'Figtree', sans-serif; box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: "";
            position: absolute;
            top: -40%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
            pointer-events: none;
        }
        body::after {
            content: "";
            position: absolute;
            bottom: -30%;
            left: -15%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            pointer-events: none;
        }
        .auth-wrapper {
            width: 100%;
            max-width: 380px;
            position: relative;
            z-index: 1;
            animation: fadeUp 0.5s ease;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .auth-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 2rem;
        }
        .auth-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .auth-logo {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #059669, #047857);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }
        .auth-logo svg { width: 22px; height: 22px; color: #fff; }
        .auth-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.02em;
        }
        .auth-title span { color: #059669; }
        .auth-subtitle {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 0.25rem;
        }
        .auth-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            background: #f9fafb;
            transition: all 0.2s ease;
            color: #111827;
        }
        .auth-input:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            background: #fff;
        }
        .auth-input::placeholder { color: #9ca3af; }
        .auth-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.375rem;
        }
        .auth-group { margin-bottom: 0.875rem; }
        .auth-btn {
            width: 100%;
            padding: 0.625rem;
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            font-weight: 700;
            font-size: 0.875rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            margin-top: 0.25rem;
        }
        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
        }
        .auth-btn:active { transform: translateY(0); }
        .auth-footer {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.8rem;
            color: #9ca3af;
        }
        .auth-footer a {
            color: #059669;
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer a:hover { text-decoration: underline; }
        .auth-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }
        .auth-back {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 10;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
        }
        .auth-back:hover { color: #fff; background: rgba(255,255,255,0.1); }
        .auth-back svg { width: 14px; height: 14px; }
        .auth-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .auth-checkbox {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            cursor: pointer;
            font-size: 0.8rem;
            color: #6b7280;
        }
        .auth-checkbox input {
            width: 15px;
            height: 15px;
            border-radius: 0.25rem;
            border: 1.5px solid #d1d5db;
            accent-color: #059669;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <a href="/" class="auth-back">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>

    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                </div>
                <div class="auth-title">Futsal<span>Book</span></div>
                <div class="auth-subtitle">{{ $subtitle ?? 'Masuk ke akun Anda' }}</div>
            </div>

            {{ $slot }}
        </div>
    </div>
</body>
</html>
