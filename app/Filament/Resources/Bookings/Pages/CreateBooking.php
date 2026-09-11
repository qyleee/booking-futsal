<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * CreateBooking - Halaman pembuatan booking baru di Filament.
 * Menangani validasi jadwal, cek bentrok, hitung total harga otomatis,
 * dan membuat transaksi terkait dalam satu transaksi database.
 */
class CreateBooking extends CreateRecord
{
    /** Resource yang terkait dengan halaman ini. */
    protected static string $resource = BookingResource::class;

    /** Menyimpan data transaksi sementara sebelum disimpan. */
    protected array $transaksiData = [];

    /**
     * Memodifikasi data form sebelum disimpan ke database.
     * - Mengisi user_id dari user yang sedang login.
     * - Memvalidasi tanggal tidak boleh di masa lalu.
     * - Memvalidasi jam selesai harus lebih besar dari jam mulai.
     * - Mengecek apakah jadwal sudah terisi (bentrok) di lapangan yang sama.
     * - Menghitung total harga otomatis berdasarkan durasi dan harga per jam.
     * - Memisahkan data transaksi (metode bayar & bukti) dari data booking.
     *
     * @param array $data Data dari form.
     * @return array Data yang sudah dimodifikasi.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        if ($data['tanggal'] < now()->toDateString()) {
            Notification::make()
                ->title('Booking Gagal')
                ->body('Tanggal Kadaluarsa.')
                ->danger()
                ->send();

            $this->halt();
        }
        $waktuMulai = Carbon::parse($data['tanggal'].' '.$data['jam_mulai']);
        $waktuSelesai = Carbon::parse($data['tanggal'].' '.$data['jam_selesai']);

        if ($waktuSelesai->lessThanOrEqualTo($waktuMulai)) {
            Notification::make()
                ->title('Booking Gagal')
                ->body('Jam selesai harus lebih besar dari jam mulai pada tanggal yang sama.')
                ->danger()
                ->send();

            $this->halt();
        }

        // Booking pada tanggal berbeda tidak dianggap bentrok, meskipun jamnya sama.
        $bentrok = Booking::where('lapangan_id', $data['lapangan_id'])
            ->where('tanggal', $data['tanggal'])
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->exists();

        if ($bentrok) {
            Notification::make()
                ->title('Booking Gagal')
                ->body('Jadwal sudah digunakan.')
                ->danger()
                ->send();

            $this->halt();
        }

        // Hitung total harga otomatis
        $lapangan = Lapangan::findOrFail($data['lapangan_id']);

        $durasi = $waktuMulai->diffInMinutes($waktuSelesai) / 60;
        $data['total_harga'] = $durasi * $lapangan->harga_per_jam;

        $this->transaksiData = [
            'metode_pembayaran' => $data['metode_pembayaran'],
            'bukti_pembayaran' => $data['bukti_pembayaran'],
        ];

        unset($data['metode_pembayaran']);
        unset($data['bukti_pembayaran']);

        return $data;
    }

    /**
     * URL redirect setelah booking berhasil dibuat.
     *
     * @return string URL halaman daftar booking.
     */
    protected function getRedirectUrl(): string
    {
        return BookingResource::getUrl();
    }

    /**
     * Menangani pembuatan record booking dan transaksi dalam satu transaksi database.
     * Membuat booking terlebih dahulu, lalu membuat transaksi terkait.
     *
     * @param array $data Data booking yang sudah valid.
     * @return Model Record booking yang baru dibuat.
     */
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Model {
            $record = new Booking($data);
            $record->save();

            Transaksi::create([
                'booking_id' => $record->id,
                'metode_pembayaran' => $this->transaksiData['metode_pembayaran'],
                'bukti_pembayaran' => $this->transaksiData['bukti_pembayaran'],
                'status_pembayaran' => 'menunggu',
            ]);

            return $record;
        });
    }
}
