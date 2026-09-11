<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Transaksi - Menyimpan data pembayaran untuk sebuah booking.
 * Setiap transaksi terkait ke satu Booking dan berisi info metode pembayaran,
 * bukti pembayaran, serta status pembayaran (menunggu/lunas/ditolak).
 */
class Transaksi extends Model
{
    /**
     * Kolom yang boleh diisi secara mass-assignable (fillable).
     * - booking_id: foreign key ke tabel bookings.
     * - metode_pembayaran: cara bayar (transfer_bank, ewallet, qris, cash).
     * - bukti_pembayaran: path file gambar bukti transfer.
     * - status_pembayaran: status (menunggu, lunas, ditolak).
     */
    protected $fillable = [
        'booking_id',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
    ];

    /**
     * Relasi: Transaksi milik satu Booking.
     * Menggunakan belongsTo karena transaksi memiliki foreign key booking_id.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
