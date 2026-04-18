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
            'nik' => 'required|string',
        ]);

        $registration = PmbRegistration::where('registration_code', $request->registration_code)
                                     ->where('nik', $request->nik)
                                     ->first();

        if (!$registration) {
            return back()->with('error', 'Nomor Registrasi atau NIK tidak ditemukan. Harap periksa kembali ketikan Anda.')->withInput();
        }

        // Store registration id in session briefly so we don't need URL params for status if unwanted, 
        // but returning the view with the model is simpler and stateless for a single check.
        // Actually we can simply return the view with the registration record injected.
        
        // Pass a flag to indicate search is complete along with the data
        return view('pmb.status', compact('registration'));
    }

    public function printLoa($registration_code)
    {
        // Require NIK from query param as a light security check if needed, but since it's just PDF, the logic here will be simple.
        // For security, ideally we store it in session, but to keep it simple, we just query by code.
        // Only accepted can print
        $registration = PmbRegistration::where('registration_code', $registration_code)
                                     ->where('status', 'accepted')
                                     ->firstOrFail();

        return view('pmb.loa', compact('registration'));
    }
}
