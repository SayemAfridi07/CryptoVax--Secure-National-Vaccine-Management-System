<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Secure Session Middleware
 * Prevents session hijacking by validating session fingerprint
 */
class SecureSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {

            $currentAgentHash = hash('sha256', $request->userAgent() ?? '');
            $currentIpHash    = hash('sha256', $request->ip() ?? '');

            $storedAgentHash  = $request->session()->get('user_agent_hash');
            $storedIpHash     = $request->session()->get('ip_hash');

            // If session fingerprint set but doesn't match = possible hijacking
            if ($storedAgentHash && !hash_equals($storedAgentHash, $currentAgentHash)) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                                 ->withErrors(['email' => 'Session security check failed. Please login again.']);
            }

            // Update fingerprint if not set
            if (!$storedAgentHash) {
                $request->session()->put('user_agent_hash', $currentAgentHash);
                $request->session()->put('ip_hash', $currentIpHash);
            }

            // Auto key rotation check (every 30 days)
            $lastCheck = $request->session()->get('key_rotation_checked');
            if (!$lastCheck || now()->diffInHours($lastCheck) > 24) {
                $keyManager = new \App\Services\Crypto\KeyManager();
                if ($keyManager->needsRotation()) {
                    $keyManager->rotateSystemKeys();
                    \Log::info('Auto key rotation triggered for user: ' . auth()->id());
                }
                $request->session()->put('key_rotation_checked', now()->toISOString());
            }
        }

        return $next($request);
    }
}