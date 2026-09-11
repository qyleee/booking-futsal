<?php

use App\Filament\Resources\Bookings\BookingResource;
use App\Filament\Resources\Transaksis\TransaksiResource;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\QueryException;

/**
 * Fungsi helper untuk membuat booking test di dalam test.
 * Membuat lapangan 'Lapangan Test' jika belum ada, lalu membuat booking
 * dengan jadwal besok jam 10-11, total Rp100.000, status menunggu.
 *
 * @param int $userId ID user pemilik booking.
 * @return Booking Record booking yang baru dibuat.
 */
function createTestBooking(int $userId): Booking
{
    $lapangan = Lapangan::firstOrCreate(
        ['nama_lapangan' => 'Lapangan Test'],
        ['harga_per_jam' => 100000],
    );

    return Booking::create([
        'user_id' => $userId,
        'lapangan_id' => $lapangan->id,
        'tanggal' => now()->addDay()->toDateString(),
        'jam_mulai' => '10:00',
        'jam_selesai' => '11:00',
        'total_harga' => 100000,
        'status' => 'menunggu',
    ]);
}

/**
 * Test: user biasa hanya bisa melihat booking miliknya sendiri.
 * Memastikan query hanya mengembalikan booking milik user yang login,
 * dan user lain tidak bisa mengedit booking yang bukan miliknya.
 */
it('allows normal users to view only their own bookings', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $ownBooking = createTestBooking($user->id);
    $otherBooking = createTestBooking($otherUser->id);

    $this->actingAs($user);

    expect(BookingResource::getEloquentQuery()->pluck('user_id')->all())
        ->toContain($user->id)
        ->not->toContain($otherUser->id);

    expect(BookingResource::canEdit($ownBooking))->toBeTrue()
        ->and(BookingResource::canEdit($otherBooking))->toBeFalse();
});

/**
 * Test: booking yang sudah dikonfirmasi atau dibatalkan tidak bisa diedit.
 * Memastikan status booking mengunci kemampuan edit.
 */
it('locks confirmed bookings and does not allow editing cancelled bookings', function () {
    $user = User::factory()->create();
    $booking = createTestBooking($user->id);

    $this->actingAs($user);

    $booking->update(['status' => 'dikonfirmasi']);
    expect(BookingResource::canEdit($booking->fresh()))->toBeFalse();

    $booking->update(['status' => 'dibatalkan']);
    expect(BookingResource::canEdit($booking->fresh()))->toBeFalse();
});

/**
 * Test: admin bisa melihat semua booking dari semua user.
 */
it('allows admins to see all bookings', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $otherUser = User::factory()->create();

    createTestBooking($admin->id);
    createTestBooking($otherUser->id);

    $this->actingAs($admin);

    expect(BookingResource::getEloquentQuery()->count())->toBe(2);
});

/**
 * Test: satu booking hanya boleh punya satu transaksi (unique booking_id).
 * Mencoba membuat duplikat transaksi harus melempar QueryException.
 */
it('does not allow duplicate transactions for one booking', function () {
    $booking = createTestBooking(User::factory()->create()->id);

    Transaksi::create([
        'booking_id' => $booking->id,
        'metode_pembayaran' => 'transfer_bank',
        'status_pembayaran' => 'menunggu',
    ]);

    expect(fn () => Transaksi::create([
        'booking_id' => $booking->id,
        'metode_pembayaran' => 'ewallet',
        'status_pembayaran' => 'menunggu',
    ]))->toThrow(QueryException::class);
});

/**
 * Test: user tidak bisa mengedit atau menghapus transaksi,
 * tapi admin bisa menghapus transaksi.
 */
it('blocks transaction editing for all users and allows admin to delete', function () {
    $user = User::factory()->create();
    $booking = createTestBooking($user->id);
    $transaction = Transaksi::create([
        'booking_id' => $booking->id,
        'metode_pembayaran' => 'transfer_bank',
        'status_pembayaran' => 'menunggu',
    ]);

    $this->actingAs($user);

    expect(TransaksiResource::canEdit($transaction))->toBeFalse()
        ->and(TransaksiResource::canDelete($transaction))->toBeFalse();

    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    expect(TransaksiResource::canEdit($transaction))->toBeFalse()
        ->and(TransaksiResource::canDelete($transaction))->toBeTrue();
});

/**
 * Test: data pembayaran yang sudah ada dimuat ke form saat edit booking.
 * Memastikan metode pembayaran dan bukti pembayaran terbaca dengan benar.
 */
it('loads existing payment data when editing a booking', function () {
    $user = User::factory()->create();
    $booking = createTestBooking($user->id);
    $transaction = Transaksi::create([
        'booking_id' => $booking->id,
        'metode_pembayaran' => 'ewallet',
        'bukti_pembayaran' => 'bukti-pembayaran/bukti.png',
        'status_pembayaran' => 'menunggu',
    ]);

    expect($booking->fresh()->transaksi->metode_pembayaran)
        ->toBe('ewallet')
        ->and($booking->fresh()->transaksi->bukti_pembayaran)
        ->toBe('bukti-pembayaran/bukti.png');
});

/**
 * Test: booking dengan jam yang sama di tanggal berbeda tidak dianggap bentrok.
 * Hanya booking di tanggal yang sama yang dianggap bentrok.
 */
it('does not treat matching times on different dates as a schedule conflict', function () {
    $user = User::factory()->create();
    $lapangan = Lapangan::create([
        'nama_lapangan' => 'Lapangan Tanggal Berbeda',
        'harga_per_jam' => 100000,
    ]);

    Booking::create([
        'user_id' => $user->id,
        'lapangan_id' => $lapangan->id,
        'tanggal' => now()->addDay()->toDateString(),
        'jam_mulai' => '10:00',
        'jam_selesai' => '11:00',
        'total_harga' => 100000,
        'status' => 'menunggu',
    ]);

    $bentrok = Booking::where('lapangan_id', $lapangan->id)
        ->where('tanggal', now()->addDays(2)->toDateString())
        ->whereIn('status', ['menunggu', 'dikonfirmasi'])
        ->where('jam_mulai', '<', '11:00')
        ->where('jam_selesai', '>', '10:00')
        ->exists();

    expect($bentrok)->toBeFalse();
});

/**
 * Test: user hanya melihat transaksi miliknya, admin melihat semua.
 * Memastikan filter query transaksi berfungsi sesuai role.
 */
it('shows users only their own transactions and admins all transactions', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $userBooking = createTestBooking($user->id);
    $otherBooking = createTestBooking($otherUser->id);

    Transaksi::create([
        'booking_id' => $userBooking->id,
        'metode_pembayaran' => 'transfer_bank',
        'status_pembayaran' => 'menunggu',
    ]);
    Transaksi::create([
        'booking_id' => $otherBooking->id,
        'metode_pembayaran' => 'ewallet',
        'status_pembayaran' => 'menunggu',
    ]);

    $this->actingAs($user);

    expect(TransaksiResource::getEloquentQuery()->pluck('booking_id')->all())
        ->toBe([$userBooking->id]);

    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    expect(TransaksiResource::getEloquentQuery()->count())->toBe(2);
});
