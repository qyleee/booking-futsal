<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * EditBooking - Halaman edit booking di Filament.
 * Menangani pembaruan data booking, validasi jadwal, cek bentrok,
 * update transaksi terkait, dan mendukung resubmission booking yang dibatalkan.
 */
class EditBooking extends EditRecord
{
    /** Resource yang terkait dengan halaman ini. */
    protected static string $resource = BookingResource::class;

    /** Menyimpan data transaksi sementara sebelum diupdate. */
    protected array $transaksiData = [];

    /**
     * Mengisi data form dengan data transaksi yang sudah ada saat edit.
     * Mengambil metode pembayaran dan bukti pembayaran dari relasi transaksi.
     *
     * @param array $data Data yang akan diisi ke form.
     * @return array Data yang sudah dilengkapi dengan data transaksi.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $transaksi = $this->record->transaksi;

        if ($transaksi) {
            $data['metode_pembayaran'] = $transaksi->metode_pembayaran;
            $data['bukti_pembayaran'] = $transaksi->bukti_pembayaran;
        }

        return $data;
    }

    /**
     * Memodifikasi data form sebelum disimpan ke database.
     * - Memvalidasi tanggal tidak boleh di masa lalu.
     * - Mengecek apakah jadwal bentrok dengan booking lain.
     * - Memvalidasi jam selesai harus lebih besar dari jam mulai.
     * - Menghitung ulang total harga berdasarkan durasi terbaru.
     * - Mendukung resubmission: jika status dibatalkan, ubah menjadi menunggu.
     * - Memisahkan data transaksi dari data booking.
     *
     * @param array $data Data dari form.
     * @return array Data yang sudah dimodifikasi.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $waktuMulai = Carbon::parse($data['tanggal'].' '.$data['jam_mulai']);
        $waktuSelesai = Carbon::parse($data['tanggal'].' '.$data['jam_selesai']);
        $bentrok = Booking::where('id', '!=', $this->record->id)
            ->where('lapangan_id', $data['lapangan_id'])
            ->where('tanggal', $data['tanggal'])
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->exists();

        if ($data['tanggal'] < now()->toDateString()) {
            Notification::make()
                ->title('Booking Gagal')
                ->body('Tanggal Kadaluarsa.')
                ->danger()
                ->send();

            $this->halt();
        }

        if ($bentrok) {
            Notification::make()
                ->title('Booking Gagal')
                ->body('Jadwal sudah digunakan.')
                ->danger()
                ->send();

            $this->halt();
        }

        if ($waktuSelesai->lessThanOrEqualTo($waktuMulai)) {
            Notification::make()
                ->title('Booking Gagal')
                ->body('Jam selesai harus lebih besar dari jam mulai pada tanggal yang sama.')
                ->danger()
                ->send();

            $this->halt();
        }

        $data['status'] = $this->record->status;

        $durasi = $waktuMulai->diffInMinutes($waktuSelesai) / 60;
        $data['total_harga'] = $durasi * Lapangan::findOrFail($data['lapangan_id'])->harga_per_jam;

        $isResubmission = $this->record->status === 'dibatalkan';

        if ($this->record->transaksi?->status_pembayaran !== 'lunas') {
            $this->transaksiData = [
                'metode_pembayaran' => $data['metode_pembayaran'] ?? null,
                'bukti_pembayaran' => $data['bukti_pembayaran'] ?? null,
                'status_pembayaran' => $isResubmission
                    ? 'menunggu'
                    : $this->record->transaksi?->status_pembayaran ?? 'menunggu',
            ];
        }

        if ($isResubmission) {
            $data['status'] = 'menunggu';
        }

        unset($data['metode_pembayaran'], $data['bukti_pembayaran']);

        return $data;
    }

    /**
     * Menangani update record booking dan transaksi dalam satu transaksi database.
     * - Update data booking.
     * - Update transaksi jika sudah ada dan belum lunas.
     * - Buat transaksi baru jika belum ada.
     *
     * @param Model $record Record booking yang akan diupdate.
     * @param array $data Data baru dari form.
     * @return Model Record booking yang sudah diupdate.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data): Model {
            $record->update($data);

            $transaksi = $record->transaksi;

            if ($transaksi && $transaksi->status_pembayaran !== 'lunas') {
                $transaksi->update($this->transaksiData);
            } elseif (! $transaksi) {
                Transaksi::create([
                    'booking_id' => $record->id,
                    ...$this->transaksiData,
                    'status_pembayaran' => $this->transaksiData['status_pembayaran'] ?? 'menunggu',
                ]);
            }

            return $record;
        });
    }

    /**
     * URL redirect setelah booking berhasil diupdate.
     *
     * @return string URL halaman daftar booking.
     */
    protected function getRedirectUrl(): string
    {
        return BookingResource::getUrl();
    }

    /**
     * Action header yang tersedia di halaman edit.
     * Menyediakan tombol hapus untuk admin.
     *
     * @return array Daftar action header.
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
