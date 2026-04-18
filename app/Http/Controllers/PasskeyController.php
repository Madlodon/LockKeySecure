<?php

namespace App\Http\Controllers;

use App\Models\Passkey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasskeyController extends Controller
{
    /**
     * Generate a server-side challenge and return WebAuthn creation options.
     */
    public function options(Request $request): JsonResponse
    {
        $challengeBytes = random_bytes(32);
        $challenge64    = $this->base64urlEncode($challengeBytes);

        $request->session()->put('webauthn_challenge', $challenge64);

        $user = auth()->user();

        return response()->json([
            'challenge'               => $challenge64,
            'rp'                      => ['name' => config('app.name'), 'id' => $request->getHost()],
            'user'                    => [
                'id'          => $this->base64urlEncode((string) $user->id),
                'name'        => $user->email,
                'displayName' => $user->name,
            ],
            'pubKeyCredParams'        => [
                ['type' => 'public-key', 'alg' => -7],
                ['type' => 'public-key', 'alg' => -257],
            ],
            'authenticatorSelection'  => ['userVerification' => 'preferred'],
            'timeout'                 => 60000,
        ]);
    }

    /**
     * Verify the WebAuthn response and store the passkey.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id'                => ['required', 'string'],
            'rawId'             => ['required', 'string'],
            'clientDataJSON'    => ['required', 'string'],
            'attestationObject' => ['required', 'string'],
            'name'              => ['nullable', 'string', 'max:100'],
        ]);

        // Decode and parse clientDataJSON
        $clientDataRaw = $this->base64urlDecode($request->clientDataJSON);
        $clientData    = json_decode($clientDataRaw, true);

        if (! $clientData) {
            return response()->json(['error' => 'clientDataJSON invalide.'], 422);
        }

        // Verify the challenge matches what the server issued
        $sessionChallenge = $request->session()->get('webauthn_challenge');
        if (! $sessionChallenge || ($clientData['challenge'] ?? '') !== $sessionChallenge) {
            return response()->json(['error' => 'Challenge invalide ou expiré.'], 422);
        }

        // Verify the origin
        $expectedOrigin = $request->getSchemeAndHttpHost();
        if (($clientData['origin'] ?? '') !== $expectedOrigin) {
            return response()->json(['error' => 'Origine invalide.'], 422);
        }

        // Verify the type
        if (($clientData['type'] ?? '') !== 'webauthn.create') {
            return response()->json(['error' => 'Type WebAuthn invalide.'], 422);
        }

        // Check this credential is not already registered
        if (Passkey::where('credential_id', $request->id)->exists()) {
            return response()->json(['error' => 'Ce passkey est déjà enregistré.'], 422);
        }

        $request->session()->forget('webauthn_challenge');

        $passkey = auth()->user()->passkeys()->create([
            'credential_id'      => $request->id,
            'public_key_response' => json_encode([
                'rawId'             => $request->rawId,
                'attestationObject' => $request->attestationObject,
            ]),
            'name' => $request->name ?: ('Passkey du ' . now()->format('d/m/Y à H:i')),
        ]);

        return response()->json([
            'success' => true,
            'passkey' => [
                'id'         => $passkey->id,
                'name'       => $passkey->name,
                'created_at' => $passkey->created_at->format('d/m/Y'),
            ],
        ]);
    }

    /**
     * Delete a passkey.
     */
    public function destroy(Passkey $passkey): JsonResponse
    {
        abort_unless($passkey->user_id === auth()->id(), 403);
        $passkey->delete();

        return response()->json(['success' => true]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64urlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
