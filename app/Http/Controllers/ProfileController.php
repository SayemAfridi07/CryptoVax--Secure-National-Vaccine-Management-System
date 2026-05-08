<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Crypto\ECCCrypto;
use App\Services\Crypto\HMACService;
use App\Services\Crypto\KeyManager;
use App\Services\Crypto\RSACrypto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     * Decrypts RSA (from registration) or ECC (from previous updates) on the fly.
     */
    public function edit(Request $request): View
    {
        $user       = $request->user();
        $keyManager = new KeyManager();
        $rsa        = new RSACrypto();
        $ecc        = new ECCCrypto();

        $decryptedData = [];

        try {
            $rsaKeys = $keyManager->getSystemRSAKeys();
            $eccKeys = $keyManager->getSystemECCKeys();

            /**
             * Smart Decryptor:
             * Checks if data is JSON (ECC) or a raw string (RSA) and decrypts accordingly.
             * Fulfills Requirement #10 (Using at least two asymmetric algorithms).
             */
            $decryptSmart = function($encryptedString) use ($rsa, $ecc, $rsaKeys, $eccKeys) {
                if (empty($encryptedString)) return null;

                $decoded = json_decode($encryptedString, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // It's ECC Data
                    $eccPrivKey = $eccKeys['private_key'];
                    if (is_array($eccPrivKey)) {
                        $eccPrivKey = $eccPrivKey['d'] ?? $eccPrivKey['secret'] ?? array_values($eccPrivKey)[0];
                    }
                    return $ecc->decrypt($decoded, (string) $eccPrivKey); 
                } else {
                    // It's RSA Data
                    $rsaPrivKey = $rsaKeys['private_key'];
                    if (is_string($rsaPrivKey)) {
                        $rsaPrivKey = json_decode($rsaPrivKey, true);
                    }
                    return $rsa->decrypt($encryptedString, (array) $rsaPrivKey); 
                }
            };

            // Decrypt all fields for the view
            $decryptedData['name']  = $decryptSmart($user->name_encrypted);
            $decryptedData['nid']   = $decryptSmart($user->nid_encrypted);
            $decryptedData['dob']   = $decryptSmart($user->dob_encrypted);
            $decryptedData['phone'] = $decryptSmart($user->phone_encrypted);

            // Requirement #8: Verify Data Integrity (MAC)
            if ($user->mac_tag) {
                $hmac = new HMACService();
                $integrityOk = $hmac->verifyUserData([
                    'name'  => $decryptedData['name'],
                    'email' => $user->email,
                    'nid'   => $decryptedData['nid'],
                    'phone' => $decryptedData['phone'],
                    'dob'   => $decryptedData['dob'],
                ], $user->mac_tag);

                if (!$integrityOk) {
                    session()->flash('warning', 'Security Alert: Data integrity could not be verified.');
                }
            }

        } catch (\Exception $e) {
            \Log::error('Profile decryption error: ' . $e->getMessage());
        }

        return view('profile.edit', [
            'user'          => $user,
            'decryptedData' => $decryptedData,
        ]);
    }

    /**
     * Update the user's profile information.
     * Re-encrypts everything using ECC to demonstrate Algorithm Rotation.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user       = $request->user();
        $keyManager = new KeyManager();
        $ecc        = new ECCCrypto();
        $hmac       = new HMACService();

        // Get ECC keys (Algorithm #2)
        $eccKeys = $keyManager->getSystemECCKeys();

        // 1. Encrypt updated data with ECC (Requirement #10)
        $nameEnc  = $ecc->encrypt($request->name,  $eccKeys['public_key']);
        $nidEnc   = $ecc->encrypt((string)$request->nid, $eccKeys['public_key']);
        $phoneEnc = $ecc->encrypt((string)$request->phone, $eccKeys['public_key']);
        $dobEnc   = $ecc->encrypt((string)$request->dob,   $eccKeys['public_key']);

        // 2. Generate new MAC for the updated data (Requirement #8)
        $macTag = $hmac->generateForUser([
            'name'  => $request->name,
            'email' => $request->email,
            'nid'   => $request->nid,
            'phone' => $request->phone,
            'dob'   => $request->dob,
        ]);

        // 3. Save only encrypted data and MAC (Requirement #7)
        $user->name_encrypted  = json_encode($nameEnc);
        $user->nid_encrypted   = json_encode($nidEnc);
        $user->phone_encrypted = json_encode($phoneEnc);
        $user->dob_encrypted   = json_encode($dobEnc);
        $user->mac_tag         = $macTag;
        
        // Plaintext email is kept for system routing/auth
        $user->email = $request->email;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}