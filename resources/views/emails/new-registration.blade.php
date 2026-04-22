<x-mail::message>
# Notifikasi Pendaftaran Baru

Alhamdulillah, ada pendaftar baru di sistem PMB Matla University.

**Detail Pendaftar:**
- **Nama:** {{ $registration->full_name }}
- **No. Registrasi:** {{ $registration->registration_code }}
- **Program Studi:** {{ $registration->study_program }}
- **WhatsApp:** [{{ $registration->whatsapp_number }}](https://wa.me/{{ $registration->whatsapp_number }})

<x-mail::button :url="route('backend.admin.pmb.registrations.show', $registration->id)">
Lihat Detail Pendaftaran
</x-mail::button>

Barakallahu fiikum,<br>
Sistem PMB {{ config('app.name') }}
</x-mail::message>
