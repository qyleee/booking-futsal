<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Models\Booking;
use App\Models\Lapangan;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

/**
 * BookingForm - Mengonfigurasi form untuk create/edit booking.
 * Berisi field-field: pilih lapangan, tanggal, jam mulai/selesai,
 * total harga (otomatis), metode pembayaran, info rekening/e-wallet/QRIS,
 * upload bukti pembayaran, dan status.
 */
class BookingForm
{
    /**
     * Mengonfigurasi semua komponen form booking.
     * Total harga dihitung otomatis saat user memilih lapangan atau mengubah jam.
     * Info metode pembayaran (rekening bank, e-wallet, QRIS) ditampilkan sesuai pilihan.
     *
     * @param Schema $schema Schema Filament.
     * @return Schema Schema yang sudah dikonfigurasi dengan semua field.
     */
    public static function configure(Schema $schema): Schema
    {
        $hitungTotalHarga = function (Get $get, Set $set): void {
            $lapanganId = $get('lapangan_id');
            $jamMulai = $get('jam_mulai');
            $jamSelesai = $get('jam_selesai');

            if (! $lapanganId || ! $jamMulai || ! $jamSelesai) {
                $set('total_harga', null);

                return;
            }

            $waktuMulai = Carbon::parse($jamMulai);
            $waktuSelesai = Carbon::parse($jamSelesai);

            if ($waktuSelesai->lessThanOrEqualTo($waktuMulai)) {
                $set('total_harga', null);

                return;
            }

            $hargaPerJam = Lapangan::find($lapanganId)?->harga_per_jam;
            $durasi = $waktuMulai->diffInMinutes($waktuSelesai) / 60;

            $set('total_harga', $durasi * $hargaPerJam);
        };

        return $schema
            ->components([
                Select::make('lapangan_id')
                    ->relationship('lapangan', 'nama_lapangan')
                    ->live()
                    ->afterStateUpdated($hitungTotalHarga)
                    ->required(),

                DatePicker::make('tanggal')
                    ->native(false)
                    ->required(),

                TimePicker::make('jam_mulai')
                    ->live()
                    ->afterStateUpdated($hitungTotalHarga)
                    ->required(),

                TimePicker::make('jam_selesai')
                    ->live()
                    ->afterStateUpdated($hitungTotalHarga)
                    ->required(),

                TextInput::make('total_harga')
                    ->prefix('Rp')
                    ->numeric()
                    ->disabled(),

                Select::make('metode_pembayaran')
                    ->options([
                        'transfer_bank' => 'Transfer Bank',
                        'ewallet' => 'E-Wallet',
                        'qris' => 'QRIS',
                    ])
                    ->live()
                    ->disabled(fn (string $operation, ?Booking $record): bool => $operation === 'edit'
                        && $record?->transaksi?->status_pembayaran === 'lunas')
                    ->required(fn (string $operation, ?Booking $record): bool => $operation === 'create'
                        || ($operation === 'edit' && $record?->status === 'dibatalkan')),

                // Info rekening bank (BCA, BRI, Mandiri) - ditampilkan jika pilih Transfer Bank
                Placeholder::make('info_transfer')
                    ->label('')
                    ->content(new HtmlString('
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                            <ul class="space-y-3">
                                <li class="flex items-center justify-between gap-4 rounded-md bg-white px-3 py-2.5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">BCA</span>
                                    <span class="font-mono text-sm font-semibold tracking-wide text-gray-950 dark:text-white">1234567890</span>
                                </li>
                                <li class="flex items-center justify-between gap-4 rounded-md bg-white px-3 py-2.5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">BRI</span>
                                    <span class="font-mono text-sm font-semibold tracking-wide text-gray-950 dark:text-white">9876543210</span>
                                </li>
                                <li class="flex items-center justify-between gap-4 rounded-md bg-white px-3 py-2.5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">Mandiri</span>
                                    <span class="font-mono text-sm font-semibold tracking-wide text-gray-950 dark:text-white">1122334455</span>
                                </li>
                            </ul>
                        </div>
                    '))
                    ->visible(fn ($get) => $get('metode_pembayaran') === 'transfer_bank'),

                // Info e-wallet (DANA, OVO, GoPay) - ditampilkan jika pilih E-Wallet
                Placeholder::make('info_ewallet')
                    ->label('')
                    ->content(new HtmlString('
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                            <ul class="space-y-3">
                                <li class="flex items-center justify-between gap-4 rounded-md bg-white px-3 py-2.5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">DANA</span>
                                    <span class="font-mono text-sm font-semibold tracking-wide text-gray-950 dark:text-white">0812 3456 7890</span>
                                </li>
                                <li class="flex items-center justify-between gap-4 rounded-md bg-white px-3 py-2.5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">OVO</span>
                                    <span class="font-mono text-sm font-semibold tracking-wide text-gray-950 dark:text-white">0813 4567 8901</span>
                                </li>
                                <li class="flex items-center justify-between gap-4 rounded-md bg-white px-3 py-2.5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">GoPay</span>
                                    <span class="font-mono text-sm font-semibold tracking-wide text-gray-950 dark:text-white">0814 5678 9012</span>
                                </li>
                            </ul>
                        </div>
                    '))
                    ->visible(fn ($get) => $get('metode_pembayaran') === 'ewallet'),

                // Info QRIS - ditampilkan jika pilih QRIS
                Placeholder::make('info_qris')
                    ->label('QRIS Pembayaran')
                    ->content(new HtmlString('
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                            <img src="'.asset('images/ChatGPT Image 3 Sep 2026, 11.33.40.png').'" style="max-width:300px;">
                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">Silakan scan QRIS di atas lalu upload bukti pembayaran.</p>
                        </div>
                    '))
                    ->visible(fn ($get) => $get('metode_pembayaran') === 'qris'),

                // Upload bukti pembayaran - wajib saat create atau resubmission
                FileUpload::make('bukti_pembayaran')
                    ->directory('bukti-pembayaran')
                    ->image()
                    ->disabled(fn (string $operation, ?Booking $record): bool => $operation === 'edit'
                        && $record?->transaksi?->status_pembayaran === 'lunas')
                    ->required(fn (string $operation, ?Booking $record): bool => $operation === 'create'
                        || ($operation === 'edit' && $record?->status === 'dibatalkan')),

                // Status booking - default menunggu, disabled (diatur otomatis)
                Select::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'dikonfirmasi' => 'Dikonfirmasi',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->default('menunggu')
                    ->disabled()
                    ->dehydrated()
                    ->required(),
            ]);
    }
}
