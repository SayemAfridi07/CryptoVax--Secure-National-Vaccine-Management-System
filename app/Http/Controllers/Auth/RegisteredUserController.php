<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Crypto\CustomHash;
use App\Services\Crypto\HMACService;
use App\Services\Crypto\KeyManager;
use App\Services\Crypto\RSACrypto;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Validate input
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'nid'      => ['required', 'numeric'],
            'dob'      => ['nullable', 'date'],
            'phone'    => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //  Check email uniqueness using email_hash
        $emailHash = (new CustomHash())->quickHash($request->email);
        if (User::where('email_hash', $emailHash)->exists()) {
            return back()->withErrors(['email' => 'This email is already registered.'])
                         ->withInput();
        }

        //  Custom password hash + salt 
        $hasher       = new CustomHash();
        $salt         = $hasher->generateSalt();
        $passwordHash = $hasher->hashPassword($request->password, $salt);

        //  Get RSA keys and encrypt user data
        $keyManager = new KeyManager();
        $rsaKeys    = $keyManager->getSystemRSAKeys();
        $rsa        = new RSACrypto();

        $nameEnc  = $rsa->encrypt($request->name,               $rsaKeys['public_key']);
        $nidEnc   = $rsa->encrypt((string) $request->nid,       $rsaKeys['public_key']);
        $dobEnc   = $rsa->encrypt((string)($request->dob   ?? ''), $rsaKeys['public_key']);
        $phoneEnc = $rsa->encrypt((string)($request->phone ?? ''), $rsaKeys['public_key']);

        //  Generate MAC tag for data integrity 
        $hmac   = new HMACService();
        $macTag = $hmac->generateForUser([
            'name'  => $request->name,
            'email' => $request->email,
            'nid'   => $request->nid,
            'phone' => $request->phone ?? '',
            'dob'   => $request->dob   ?? '',
        ]);

        //  Save user — plaintext ONLY for email/name/password
        // Everything else stored ONLY as encrypted
        $user = User::create([
            // Minimum plaintext needed for Laravel Auth
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => $passwordHash,

            // Custom password fields
            'password_salt'   => $salt,
            'password_custom' => $passwordHash,

            // RSA Encrypted versions of sensitive data
            'name_encrypted'  => $nameEnc,
            'nid_encrypted'   => $nidEnc,
            'dob_encrypted'   => $dobEnc,
            'phone_encrypted' => $phoneEnc,

            // Lookup hash + MAC
            'email_hash'      => $emailHash,
            'mac_tag'         => $macTag,
            
        ]);

        event(new Registered($user));

        // Redirect to login — user must go through 2FA
        return redirect()->route('login')
                         ->with('status', 'Registration successful! Please login to continue.');
    }
}