<?php

namespace App\Http\Controllers;

use App\Models\PmbRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\ProgramStudi;

class PmbRegistrationController extends Controller
{
    public function create()
    {
        $programs = ProgramStudi::active()->get();
        return view('pmb.register', compact('programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Section 1: Data Pribadi
            'full_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\\s]+$/', // Letters and spaces only
            ],
            'reference' => 'nullable|string|max:255',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'whatsapp_number' => [
                'required',
                'string',
                'min:10',
                'max:15',
                'regex:/^[0-9]+$/',
                'unique:pmb_registrations,whatsapp_number',
            ],
            'email' => 'required|email:rfc,dns|max:255|unique:pmb_registrations,email',
            'address' => 'required|string|max:500',
            'activity_status' => 'required|string|max:255',
            'school_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\\s]+$/',
            ],
            'graduation_year' => [
                'required',
                'digits:4',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],
            'study_program' => 'required|string|max:255',
            'registration_type' => 'required|in:pai,idad',

            // Section 2: Ilmu Syar'i & Technology
            'main_interest' => 'required|string|max:255',
            'tech_experience' => 'required|string|max:255',
            'skill_to_learn' => 'required|string|max:255',
            'motivation' => 'required|string', // Motivasi Kuliah
            'urgency_opinion' => 'required|string',
            'future_career' => 'required|string',
            'degree_importance' => 'required|string',
            'commitment_check' => 'required|accepted',

            // Honeypot field to trap bots
            'website' => 'nullable|max:0',

            // Section 4: Administrasi
            'payment_proof' => 'required|file|image|max:5120', // Max 5MB
        ], [
            'full_name.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'whatsapp_number.min' => 'Nomor WhatsApp minimal 10 digit.',
            'email.email' => 'Format email tidak valid atau domain tidak ditemukan.',
            'email.unique' => 'Email ini sudah terdaftar dalam sistem PMB kami.',
            'whatsapp_number.unique' => 'Nomor WhatsApp ini sudah terdaftar.',
            'commitment_check.accepted' => 'Anda harus menyetujui komitmen belajar.',
            'website.max' => 'Bot detected.',
        ]);

        // Mapping checkbox commitment to boolean
        $validated['commitment_check'] = $request->has('commitment_check');

        // Handle payment proof upload
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
