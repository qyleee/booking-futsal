<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Booking Lapangan Futsal') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        .hero-gradient {
            background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .step-number {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #059669;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
    </style>
</head>
<body class="font-sans antialiased bg-white text-gray-900">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-gray-900">Futsal<span class="text-emerald-600">Book</span></span>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#lapangan" class="text-sm font-medium text-gray-600 hover:text-emerald-600 transition">Lapangan</a>
                    <a href="#cara-booking" class="text-sm font-medium text-gray-600 hover:text-emerald-600 transition">Cara Booking</a>
                    <a href="#faq" class="text-sm font-medium text-gray-600 hover:text-emerald-600 transition">FAQ</a>
                </div>
                <div class="flex items-center gap-3">
                    <a href="/login" class="text-sm font-medium text-gray-600 hover:text-emerald-600 transition">Masuk</a>
                    <a href="/register" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero-gradient relative overflow-hidden pt-32 pb-20 lg:pt-40 lg:pb-28">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 800 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="400" cy="300" r="250" stroke="white" stroke-width="2"/>
                <line x1="400" y1="50" x2="400" y2="550" stroke="white" stroke-width="1.5"/>
                <line x1="150" y1="300" x2="650" y2="300" stroke="white" stroke-width="1.5"/>
                <circle cx="400" cy="300" r="60" stroke="white" stroke-width="1.5"/>
            </svg>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-emerald-100 text-sm font-medium">Booking mudah, main langsung!</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                Sewa Lapangan Futsal<br>
                <span class="text-emerald-200">Kapan Saja, Di Sini</span>
            </h1>
            <p class="text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto mb-10">
                Booking lapangan futsal favoritmu secara online. Cepat, praktis, dan tanpa ribet. Pilih waktu, bayar, dan langsung main!
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#lapangan" class="inline-flex items-center px-8 py-3.5 bg-white text-emerald-700 font-bold rounded-xl hover:bg-emerald-50 transition shadow-lg">
                    Lihat Lapangan
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
                <a href="#cara-booking" class="inline-flex items-center px-8 py-3.5 border-2 border-white/40 text-white font-semibold rounded-xl hover:bg-white/10 transition">
                    Cara Booking
                </a>
            </div>
        </div>
    </section>

    {{-- STATS BAR --}}
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl font-extrabold text-emerald-600">{{ $lapangans->count() }}</div>
                    <div class="text-sm text-gray-500 mt-1">Lapangan Tersedia</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-emerald-600">24/7</div>
                    <div class="text-sm text-gray-500 mt-1">Buka Setiap Hari</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-emerald-600">500+</div>
                    <div class="text-sm text-gray-500 mt-1">Pemain Aktif</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-emerald-600">100%</div>
                    <div class="text-sm text-gray-500 mt-1">Lapangan Terawat</div>
                </div>
            </div>
        </div>
    </section>

    {{-- LAPANGAN / PRICING --}}
    <section id="lapangan" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 fade-up">
                <span class="inline-block text-emerald-600 font-semibold text-sm uppercase tracking-wider mb-2">Pilihan Lapangan</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Pilih Lapangan Favoritmu</h2>
                <p class="text-gray-500 mt-3 max-w-xl mx-auto">Tersedia beberapa lapangan dengan fasilitas terbaik untuk pengalaman bermain yang maksimal.</p>
            </div>

            @if($lapangans->isEmpty())
                <div class="text-center py-16">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-gray-400 text-lg">Belum ada lapangan tersedia.</p>
                </div>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($lapangans as $lapangan)
                        <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 fade-up">
                            <div class="h-48 relative overflow-hidden">
                                @if($lapangan->image)
                                    <img src="{{ asset('images/' . $lapangan->image) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white/30" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-white text-xs font-semibold">
                                    Aktif
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $lapangan->nama_lapangan }}</h3>
                                <div class="flex items-baseline gap-1 mb-4">
                                    <span class="text-3xl font-extrabold text-emerald-600">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</span>
                                    <span class="text-gray-400 text-sm">/ jam</span>
                                </div>
                                <ul class="space-y-2 mb-6">
                                    <li class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Lapangan syntetic quality
                                    </li>
                                    <li class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Penerangan lampu indoor
                                    </li>
                                    <li class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Ganti gratis jika lapangan rusak
                                    </li>
                                </ul>
                                @auth
                                    <a href="/user/bookings/create?lapangan_id={{ $lapangan->id }}" class="block w-full text-center px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition">
                                        Booking Sekarang
                                    </a>
                                @else
                                    <a href="/login" class="block w-full text-center px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition">
                                        Login untuk Booking
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- CARA BOOKING --}}
    <section id="cara-booking" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 fade-up">
                <span class="inline-block text-emerald-600 font-semibold text-sm uppercase tracking-wider mb-2">Mudah & Cepat</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Cara Booking</h2>
                <p class="text-gray-500 mt-3 max-w-xl mx-auto">Hanya 4 langkah sederhana untuk sewa lapangan futsal favoritmu.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center fade-up">
                    <div class="step-number mx-auto mb-4">1</div>
                    <h3 class="font-bold text-gray-900 mb-2">Daftar Akun</h3>
                    <p class="text-sm text-gray-500">Buat akun gratis dalam hitungan detik.</p>
                </div>
                <div class="text-center fade-up">
                    <div class="step-number mx-auto mb-4">2</div>
                    <h3 class="font-bold text-gray-900 mb-2">Pilih Lapangan & Waktu</h3>
                    <p class="text-sm text-gray-500">Tentukan lapangan dan jadwal yang kamu inginkan.</p>
                </div>
                <div class="text-center fade-up">
                    <div class="step-number mx-auto mb-4">3</div>
                    <h3 class="font-bold text-gray-900 mb-2">Bayar & Upload Bukti</h3>
                    <p class="text-sm text-gray-500">Lakukan pembayaran lalu unggah bukti transfer.</p>
                </div>
                <div class="text-center fade-up">
                    <div class="step-number mx-auto mb-4">4</div>
                    <h3 class="font-bold text-gray-900 mb-2">Main!</h3>
                    <p class="text-sm text-gray-500">Setelah dikonfirmasi, kamu langsung bisa main.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- METODE PEMBAYARAN --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 fade-up">
                <span class="inline-block text-emerald-600 font-semibold text-sm uppercase tracking-wider mb-2">Fleksibel</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Metode Pembayaran</h2>
                <p class="text-gray-500 mt-3 max-w-xl mx-auto">Pilih cara bayar yang paling nyaman buat kamu.</p>
            </div>

            <div class="grid grid-cols-3 gap-6 max-w-3xl mx-auto">
                <div class="card-hover bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100 fade-up">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Transfer Bank</span>
                </div>
                <div class="card-hover bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100 fade-up">
                    <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">E-Wallet</span>
                </div>
                <div class="card-hover bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100 fade-up">
                    <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">QRIS</span>
                </div>

            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 fade-up">
                <span class="inline-block text-emerald-600 font-semibold text-sm uppercase tracking-wider mb-2">Pertanyaan Umum</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">FAQ</h2>
            </div>

            <div class="space-y-4">
                <div class="border border-gray-200 rounded-xl p-6 fade-up">
                    <h3 class="font-bold text-gray-900 mb-2">Bagaimana cara booking lapangan?</h3>
                    <p class="text-sm text-gray-500">Daftar akun, pilih lapangan dan waktu, lakukan pembayaran, dan upload bukti pembayaran. Admin akan mengkonfirmasi booking kamu.</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-6 fade-up">
                    <h3 class="font-bold text-gray-900 mb-2">Apakah bisa batalkan booking?</h3>
                    <p class="text-sm text-gray-500">Bisa, selama status booking masih "Menunggu". Kamu bisa mengedit atau membatalkan booking dari dashboard.</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-6 fade-up">
                    <h3 class="font-bold text-gray-900 mb-2">Metode pembayaran apa saja yang diterima?</h3>
                    <p class="text-sm text-gray-500">Kami menerima transfer bank, e-wallet, dan QRIS. Pilih metode yang paling nyaman saat booking.</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-6 fade-up">
                    <h3 class="font-bold text-gray-900 mb-2">Jam operasional lapangan?</h3>
                    <p class="text-sm text-gray-500">Lapangan buka setiap hari. Kamu bisa booking slot waktu sesuai jam operasional yang tersedia.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 hero-gradient relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 800 400" fill="none"><circle cx="600" cy="200" r="300" stroke="white" stroke-width="2"/><circle cx="200" cy="300" r="200" stroke="white" stroke-width="1.5"/></svg>
        </div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-up">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Siap Main Futsal?</h2>
            <p class="text-emerald-100 text-lg mb-8">Jangan tunggu lagi! Booking lapangan sekarang dan nikmati pengalaman bermain futsal terbaik.</p>
            @auth
                <a href="/user/bookings/create" class="inline-flex items-center px-8 py-4 bg-white text-emerald-700 font-bold rounded-xl hover:bg-emerald-50 transition shadow-lg text-lg">
                    Booking Sekarang
                </a>
            @else
                <a href="/register" class="inline-flex items-center px-8 py-4 bg-white text-emerald-700 font-bold rounded-xl hover:bg-emerald-50 transition shadow-lg text-lg">
                    Daftar Gratis
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            @endauth
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                        </div>
                        <span class="font-bold text-white">FutsalBook</span>
                    </div>
                    <p class="text-sm">Platform booking lapangan futsal online yang mudah dan praktis.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#lapangan" class="hover:text-emerald-400 transition">Lapangan</a></li>
                        <li><a href="#cara-booking" class="hover:text-emerald-400 transition">Cara Booking</a></li>
                        <li><a href="#faq" class="hover:text-emerald-400 transition">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Jl. Contoh No. 123, Kota
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            info@futsalbook.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            +62 812-3456-7890
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                &copy; {{ date('Y') }} FutsalBook. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
    </script>
</body>
</html>
