<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Lapangan - Menyimpan data lapangan futsal yang tersedia.
 * Setiap lapangan memiliki nama, harga sewa per jam, dan gambar.
 */
class Lapangan extends Model
{
    /**
     * Kolom yang boleh diisi secara mass-assignable (fillable).
     * - nama_lapangan: nama identitas lapangan.
     * - harga_per_jam: biaya sewa per jam dalam Rupiah.
     * - image: nama file gambar lapangan.
     */
    protected $fillable = [
        'nama_lapangan',
        'harga_per_jam',
        'image',
    ];
}
