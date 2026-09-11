<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Booking - Menyimpan data pemesanan lapangan futsal.
 * Setiap booking terhubung ke satu User (pemesan) dan satu Lapangan.
 * Status booking: menunggu, dikonfirmasi, selesai, dibatalkan.
 */
class Booking extends Model
{
    /**
     * Kolom yang boleh diisi secara mass-assignable (fillable).
     * Meliputi: user_id, lapangan_id, tanggal, jam_mulai, jam_selesai, total_harga, status.
     */
    protected $fillable = [
        'user_id',
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'status',
    ];

    /**
     * Mengonversi tipe data kolom tertentu secara otomatis.
     * - tanggal dikonversi ke tipe Carbon/DateTime.
     * - total_harga dikonversi ke tipe integer.
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'total_harga' => 'integer',
        ];
    }

    /**
     * Relasi: Booking milik satu User (pemesan).
     * Menggunakan belongsTo karena booking memiliki foreign key user_id.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Booking terkait ke satu Lapangan.
     * Menggunakan belongsTo karena booking memiliki foreign key lapangan_id.
     */
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    /**
     * Relasi: Booking memiliki satu Transaksi (data pembayaran).
     * Menggunakan hasOne karena setiap booking hanya punya satu transaksi.
     */
    public function transaksi()
    {
    return $this->hasOne(Transaksi::class);
    }
}
