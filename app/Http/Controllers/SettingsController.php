<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class SettingsController extends Controller
{
    // ── Page principale ──────────────────────────────────────────────

    public function index(): View
    {
        $sessions = DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($s) {
                $s->is_current = $s->id === session()->getId();
                $s->last_activity_human = \Carbon\Carbon::createFromTimestamp($s->last_activity)->diffForHumans();
                return $s;
            });

        $passkeys = auth()->user()->passkeys()->latest()->get();

        return view('settings', compact('sessions', 'passkeys'));
    }

    // ── 2FA ─────────────────────────────────────────────────────────

    public function twoFactorSetup(): View
    {
        $google2fa = new Google2FA();
        $user = auth()->user();

        if (! $user->two_factor_secret) {
            $user->two_factor_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        $qrImage = \BaconQrCode\Renderer\ImageRenderer::class;

        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrSvg  = base64_encode($writer->writeString($qrCodeUrl));

        return view('settings', [
            'tab'      => '2fa',
            'qrSvg'    => $qrSvg,
            'secret'   => $user->two_factor_secret,
            'sessions' => collect(),
            'passkeys' => $user->passkeys()->latest()->get(),
        ]);
    }

    public function twoFactorEnable(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $google2fa = new Google2FA();
        $user = auth()->user();

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (! $valid) {
            return back()->withErrors(['code' => 'Code invalide. Réessaie.'])->with('tab', '2fa');
        }

        $user->two_factor_enabled = true;
        $user->save();

        return redirect()->route('settings')->with('success', 'Double authentification activée.');
    }

    public function twoFactorDisable(): RedirectResponse
    {
        $user = auth()->user();
        $user->two_factor_enabled = false;
        $user->two_factor_secret  = null;
        $user->save();

        return redirect()->route('settings')->with('success', 'Double authentification désactivée.');
    }

    // ── Mot de passe ─────────────────────────────────────────────────

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $rules = ['new_password' => ['required', 'string', 'min:8', 'confirmed']];

        if ($user->password) {
            $rules['current_password'] = ['required', 'string'];
            $request->validate($rules);

            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.'])->with('tab', 'password');
            }
        } else {
            $request->validate($rules);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('settings')->with('success', 'Mot de passe mis à jour.');
    }

    // ── Sessions ─────────────────────────────────────────────────────

    public function destroySession(Request $request, string $sessionId): RedirectResponse
    {
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->delete();

        return redirect()->route('settings')->with('success', 'Session déconnectée.');
    }

    public function destroyAllSessions(): RedirectResponse
    {
        DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', '!=', session()->getId())
            ->delete();

        return redirect()->route('settings')->with('success', 'Toutes les autres sessions ont été déconnectées.');
    }
}
