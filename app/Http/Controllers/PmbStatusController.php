<?php

namespace App\Http\Controllers;

use App\Models\PmbRegistration;
use Illuminate\Http\Request;

class PmbStatusController extends Controller
{
    public function index()
    {
        return view('pmb.status');
    }

    public function check(Request $request)
    {
        $request->validate([
            'registration_code' => 'required|string',
            'whatsapp_number' => 'required|string',
        ]);

        $registration = PmbRegistration::where('registration_code', $request->registration_code)
                                     ->where('whatsapp_number', $request->whatsapp_number)
                                     ->first();

        if (!$registration) {
            return back()->with('error', 'Kombinasi Nomor Registrasi dan Nomor WhatsApp tidak ditemukan. Harap periksa kembali.')->withInput();
        }

        // Store registration id in session briefly so we don't need URL params for status if unwanted, 
        // but returning the view with the model is simpler and stateless for a single check.
        // Actually we can simply return the view with the registration record injected.
        
        // Pass a flag to indicate search is complete along with the data
        session(['pmb_whatsapp_number' => $request->whatsapp_number]);
        
        return view('pmb.status', compact('registration'));
    }

    public function printLoa($registration_code)
    {
        $whatsappNumber = session('pmb_whatsapp_number');
        if (!$whatsappNumber) {
            abort(403, 'Akses ditolak. Sesi tidak valid atau telah berakhir. Silakan cek status pendaftaran kembali.');
        }

        $registration = PmbRegistration::where('registration_code', $registration_code)
                                     ->where('whatsapp_number', $whatsappNumber)
                                     ->where('status', 'accepted')
                                     ->firstOrFail();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pmb.loa', compact('registration'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('LoA_Matla_' . $registration->registration_code . '.pdf');
    }
}
