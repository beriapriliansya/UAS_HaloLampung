<x-mail::message>
# Konfirmasi Langganan Newsletter Anda

Halo,

Terima kasih telah mendaftar untuk menerima newsletter dari {{ config('app.name') }}.
Silakan klik tombol di bawah ini untuk mengkonfirmasi alamat email Anda dan menyelesaikan proses langganan.

<x-mail::button :url="$confirmationUrl">
Konfirmasi Email Saya
</x-mail::button>

Jika Anda tidak merasa mendaftar, abaikan email ini.

Salam,<br>
Tim {{ config('app.name') }}
</x-mail::message>