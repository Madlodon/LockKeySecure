<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ApiController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants invalides.'], 401);
        }

        $token = $user->createToken('extension')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté.']);
    }

    public function services(Request $request): JsonResponse
    {
        $query = $request->user()->services()->latest();

        if ($request->filled('domain')) {
            $domain = $request->input('domain');
            $query->where('url', 'like', "%{$domain}%");
        }

        $services = $query->get()->map(fn($s) => [
            'id'         => $s->id,
            'name'       => $s->name,
            'url'        => $s->url,
            'account_id' => $s->account_id,
            'password'   => $s->password,
        ]);

        return response()->json($services);
    }
}