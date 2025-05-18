<x-mail::message>
# Konfirmasi Pemesanan & Tiket Wisata Anda

Halo **{{ $booking->user->name }}**,

Terima kasih telah melakukan pemesanan melalui {{ config('app.name') }}. Pembayaran Anda untuk pemesanan dengan kode **{{ $booking->booking_code }}** telah berhasil kami terima.

Berikut adalah detail pemesanan Anda:

*   **Kode Booking:** {{ $booking->booking_code }}
*   **Destinasi:** {{ $booking->destination->name }}
*   **Tanggal Kunjungan:** {{ $booking->booking_date->format('d F Y') }}
*   **Jumlah Orang:** {{ $booking->num_tickets }}
*   **Total Pembayaran:** Rp {{ number_format($booking->total_amount, 0, ',', '.') }}

@if($booking->facilities->isNotEmpty())
**Fasilitas Tambahan:**
<ul>
@foreach($booking->facilities as $facility)
    <li>{{ $facility->name }} ({{ $facility->pivot->quantity }})</li>
@endforeach
</ul>
@endif

---

## Tiket Elektronik Anda

Gunakan QR Code di bawah ini atau tunjukkan kode tiket saat check-in di lokasi:

**Kode Tiket:** `{{ $booking->ticket->ticket_code ?? 'N/A' }}`

@if($qrCodeDataUri)
<div style="text-align: center; margin-top: 20px; margin-bottom: 20px;">
    <img src="{{ $qrCodeDataUri }}" alt="QR Code Tiket" style="max-width: 250px; height: auto;">
</div>
@else
<x-mail::panel>
Gagal memuat QR Code. Mohon gunakan Kode Tiket di atas untuk check-in.
</x-mail::panel>
@endif

**Instruksi Check-in:**
1.  Datang ke lokasi {{ $booking->destination->name }} pada tanggal {{ $booking->booking_date->format('d F Y') }}.
2.  Tunjukkan email ini atau Kode Tiket/QR Code kepada petugas di pintu masuk.
3.  Patuhi peraturan yang berlaku di lokasi wisata.

@if($booking->destination->operating_hours)
Jam Operasional: {{ $booking->destination->operating_hours }}
@endif
@if($booking->destination->location)
Lokasi: {{ $booking->destination->location }}
@endif

<x-mail::button :url="route('home')"> Kunjungi Website Kami </x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>