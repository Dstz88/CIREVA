<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'string', 'in:user,organizer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'spk_agreement' => ['required_if:role,organizer', 'accepted_if:role,organizer'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal :min karakter.',
            'spk_agreement.required_if' => 'Anda wajib menyetujui Persyaratan SPK (15%) untuk mendaftar sebagai Organizer.',
            'spk_agreement.accepted_if' => 'Anda wajib menyetujui Persyaratan SPK (15%) untuk mendaftar sebagai Organizer.',
        ]);

        $roleName = $request->role === 'organizer' ? 'organizer' : 'user';

        $user = User::create([
            'role' => $roleName,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'organizer') {
            $phoneInput = $request->phone;
            $cleanPhone = ($phoneInput && !filter_var($phoneInput, FILTER_VALIDATE_EMAIL)) ? substr($phoneInput, 0, 50) : null;

            $profile = \App\Models\OrganizerProfile::create([
                'user_id' => $user->id,
                'organization_name' => $user->name,
                'owner_name' => $user->name,
                'phone' => $cleanPhone,
                'address' => null,
                'description' => null,
                'status' => \App\Enums\OrganizerStatus::Pending,
            ]);

            \App\Models\CooperationAgreement::create([
                'organizer_profile_id' => $profile->id,
                'agreement_number' => 'SPK-' . str_pad($profile->id, 5, '0', STR_PAD_LEFT) . '-15PCT',
                'version' => 'v1.0',
                'file_path' => null,
                'signed_at' => now(),
                'status' => \App\Enums\SpkStatus::Signed,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
