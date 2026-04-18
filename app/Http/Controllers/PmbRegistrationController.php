<?php

namespace App\Http\Controllers;

use App\Models\PmbRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PmbRegistrationController extends Controller
{
    public function create()
    {
        return view('pmb.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Section 1: Data Pribadi
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'nik' => 'required|string|max:20|unique:pmb_registrations,nik',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'whatsapp_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'activity_status' => 'required|string|max:255',

            // Section 2: Data Pendidikan
            'last_education' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'graduation_year' => 'required|string|size:4',
            'study_program' => 'required|string|max:255',

            // Section 3: Ilmu Syar'i & Tech
            'skill_level' => 'required|integer|min:1|max:100',
            'skill_100_desc' => 'nullable|string',
            'urgency_opinion' => 'required|string',
            'focus_opinion' => 'required|string',
            'comparison_opinion' => 'required|string',
            'target_skill' => 'required|string|max:255',
            'main_interest' => 'required|string|max:255',
            'motivation' => 'required|string',

            // Section 4: Administrasi
            'payment_proof' => 'required|file|image|max:5120', // Max 5MB
        ]);

        // Handle file upload
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('pmb_payments', 'public');
            $validated['payment_proof'] = $path;
        }

        // Generate Registration Code
        $year = date('Y');
        $count = PmbRegistration::whereYear('created_at', $year)->count() + 1;
        $validated['registration_code'] = 'PMB-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Store to database
        $registration = PmbRegistration::create($validated);

        return redirect()->route('pmb.success', $registration->registration_code);
    }

    public function success($registration_code)
    {
        $registration = PmbRegistration::where('registration_code', $registration_code)->firstOrFail();
        return view('pmb.success', compact('registration'));
    }
}
