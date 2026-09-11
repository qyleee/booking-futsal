<div style="font-size: 14px; line-height: 1.6;">
    <div style="display: flex; padding: 6px 0; border-bottom: 1px solid #f3f4f6;">
        <span style="width: 120px; flex-shrink: 0; color: #6b7280;">Nama</span>
        <span style="color: #9ca3af; margin-right: 4px;">:</span>
        <span style="font-weight: 500; color: #111827;">{{ $record->booking->user->name }}</span>
    </div>
    <div style="display: flex; padding: 6px 0; border-bottom: 1px solid #f3f4f6;">
        <span style="width: 120px; flex-shrink: 0; color: #6b7280;">Email</span>
        <span style="color: #9ca3af; margin-right: 4px;">:</span>
        <span style="font-weight: 500; color: #111827;">{{ $record->booking->user->email }}</span>
    </div>
    <div style="display: flex; padding: 6px 0; border-bottom: 1px solid #f3f4f6;">
        <span style="width: 120px; flex-shrink: 0; color: #6b7280;">Lapangan</span>
        <span style="color: #9ca3af; margin-right: 4px;">:</span>
        <span style="font-weight: 500; color: #111827;">{{ $record->booking->lapangan->nama_lapangan }}</span>
    </div>
    <div style="display: flex; padding: 6px 0; border-bottom: 1px solid #f3f4f6;">
        <span style="width: 120px; flex-shrink: 0; color: #6b7280;">Tanggal</span>
        <span style="color: #9ca3af; margin-right: 4px;">:</span>
        <span style="font-weight: 500; color: #111827;">{{ $record->booking->tanggal->format('d M Y') }}</span>
    </div>
    <div style="display: flex; padding: 6px 0; border-bottom: 1px solid #f3f4f6;">
        <span style="width: 120px; flex-shrink: 0; color: #6b7280;">Jam</span>
        <span style="color: #9ca3af; margin-right: 4px;">:</span>
        <span style="font-weight: 500; color: #111827;">{{ $record->booking->jam_mulai }} - {{ $record->booking->jam_selesai }}</span>
    </div>
    <div style="display: flex; padding: 6px 0; border-bottom: 1px solid #f3f4f6;">
        <span style="width: 120px; flex-shrink: 0; color: #6b7280;">Metode Bayar</span>
        <span style="color: #9ca3af; margin-right: 4px;">:</span>
        <span style="font-weight: 500; color: #111827;">{{ match($record->metode_pembayaran) { 'transfer_bank' => 'Transfer Bank', 'ewallet' => 'E-Wallet', 'qris' => 'QRIS', 'cash' => 'Cash', default => $record->metode_pembayaran } }}</span>
    </div>
    <div style="display: flex; padding: 6px 0;">
        <span style="width: 120px; flex-shrink: 0; color: #6b7280;">Total Harga</span>
        <span style="color: #9ca3af; margin-right: 4px;">:</span>
        <span style="font-weight: 700; color: #111827;">Rp {{ number_format($record->booking->total_harga, 0, ',', '.') }}</span>
    </div>

    @if(filled($record->bukti_pembayaran))
        <div style="margin-top: 20px;" x-data="{ open: false }">
            <p style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; margin-bottom: 8px;">Bukti Pembayaran</p>
            <div
                style="text-align: center; border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px; cursor: pointer;"
                @click="open = true"
            >
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::disk(config('filament.default_filesystem_disk'))->temporaryUrl($record->bukti_pembayaran, now()->addMinutes(30)) }}"
                    alt="Bukti Pembayaran"
                    style="max-height: 40vh; border-radius: 8px; object-fit: contain;"
                >
                <p style="font-size: 11px; color: #9ca3af; margin-top: 4px;">Klik untuk memperbesar</p>
            </div>

            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="open = false"
                style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100vw !important; height: 100vh !important; z-index: 99999 !important; background: rgba(0,0,0,0.85); display: flex !important; align-items: center !important; justify-content: center !important; margin: 0 !important; padding: 20px !important; cursor: pointer; overflow: hidden;"
            >
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::disk(config('filament.default_filesystem_disk'))->temporaryUrl($record->bukti_pembayaran, now()->addMinutes(30)) }}"
                    alt="Bukti Pembayaran"
                    style="position: relative; max-width: 90vw; max-height: 90vh; border-radius: 8px; object-fit: contain; display: block; margin: auto;"
                >
            </div>
        </div>
    @endif
</div>
