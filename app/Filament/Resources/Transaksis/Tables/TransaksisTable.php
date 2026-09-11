<?php

namespace App\Filament\Resources\Transaksis\Tables;

use App\Models\Transaksi;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

/**
 * TransaksisTable - Mengonfigurasi tabel untuk daftar transaksi di Filament.
 * Menampilkan kolom: no, user, lapangan, tanggal, metode bayar, bukti, status, dan tanggal transaksi.
 * Menyediakan aksi konfirmasi pembayaran (lunas/tolak) untuk admin.
 */
class TransaksisTable
{
    /**
     * Mengonfigurasi tabel transaksi dengan kolom, filter, dan aksi.
     * - Bukti pembayaran bisa diklik untuk melihat dalam modal.
     * - Aksi "Konfirmasi Pembayaran" hanya muncul untuk admin dengan status menunggu.
     * - Admin bisa menolak atau mengkonfirmasi lunas, yang juga mengubah status booking.
     *
     * @param Table $table Tabel Filament.
     * @return Table Tabel yang sudah dikonfigurasi.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('booking.user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('booking.lapangan.nama_lapangan')
                    ->label('Lapangan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('booking.tanggal')
                    ->label('Tanggal Booking')
                    ->date()
                    ->sortable(),

                // Kolom metode pembayaran dengan badge warna
                TextColumn::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'transfer_bank' => 'Transfer Bank',
                        'ewallet' => 'E-Wallet',
                        'qris' => 'QRIS',
                        'cash' => 'Cash',
                        default => ucwords(str_replace('_', ' ', $state)),
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transfer_bank' => 'info',
                        'ewallet' => 'success',
                        'qris' => 'warning',
                        'cash' => 'gray',
                        default => 'gray',
                    }),

                // Kolom bukti pembayaran - klik untuk melihat gambar dalam modal
                ImageColumn::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->action(
                        Action::make('lihatBuktiPembayaran')
                            ->label('Bukti Pembayaran')
                            ->modal()
                            ->modalHeading('Bukti Pembayaran')
                            ->modalContent(fn (Transaksi $record) => view(
                                'filament.transaksis.bukti-pembayaran',
                                [
                                    'url' => Storage::disk(config('filament.default_filesystem_disk'))->temporaryUrl(
                                        $record->bukti_pembayaran,
                                        now()->addMinutes(30),
                                    ),
                                ],
                            ))
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                            ->visible(fn (Transaksi $record): bool => filled($record->bukti_pembayaran))
                    ),

                // Kolom status pembayaran dengan badge warna
                TextColumn::make('status_pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'lunas' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                // Aksi konfirmasi pembayaran - hanya untuk admin dengan status menunggu
                Action::make('ubahStatusPembayaran')
                    ->label('Konfirmasi Pembayaran')
                    ->icon('heroicon-o-shield-check')
                    ->color('warning')
                    ->modalHeading('Konfirmasi Pembayaran')
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false)
                    ->modalContent(fn (Transaksi $record) => view(
                        'filament.transaksis.validasi-pembayaran',
                        [
                            'record' => $record,
                        ],
                    ))
                    ->modalFooterActions([
                        // Tombol tolak - mengubah status transaksi dan booking menjadi dibatalkan
                        Action::make('tolak')
                            ->label('Ditolak')
                            ->color('danger')
                            ->icon('heroicon-o-x-mark')
                            ->action(function (Transaksi $record): void {
                                $record->update([
                                    'status_pembayaran' => 'ditolak',
                                ]);

                                $record->booking->update([
                                    'status' => 'dibatalkan',
                                ]);

                                redirect()->route('filament.admin.resources.transaksis.index');
                            })
                            ->successNotificationTitle('Pembayaran ditolak'),
                        // Tombol lunas - mengubah status transaksi menjadi lunas dan booking menjadi dikonfirmasi
                        Action::make('lunas')
                            ->label('Lunas')
                            ->color('success')
                            ->icon('heroicon-o-check')
                            ->action(function (Transaksi $record): void {
                                $record->update([
                                    'status_pembayaran' => 'lunas',
                                ]);

                                $record->booking->update([
                                    'status' => 'dikonfirmasi',
                                ]);

                                redirect()->route('filament.admin.resources.transaksis.index');
                            })
                            ->successNotificationTitle('Pembayaran dikonfirmasi lunas'),
                    ])
                    ->visible(fn ($record): bool => $record
                        && auth()->user()?->role === 'admin'
                        && ! in_array($record->status_pembayaran, ['lunas', 'ditolak'], true)),
            ]);
    }
}
