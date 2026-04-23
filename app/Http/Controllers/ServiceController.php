<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = auth()->user()->services()->latest()->get();
        return view('dashboard', compact('services'));
    }

    public function create(): View
    {
        return view('services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('url')) {
            $url = trim($request->input('url'));
            if (! preg_match('#^https?://#i', $url)) {
                $request->merge(['url' => 'https://' . $url]);
            }
        }

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'url'        => ['nullable', 'url', 'max:500'],
            'account_id' => ['nullable', 'string', 'max:255'],
            'password'   => ['required', 'string'],
            'notes'      => ['nullable', 'string', 'max:5000'],
        ]);

        auth()->user()->services()->create($data);

        return redirect()->route('dashboard')->with('success', 'Service ajouté avec succès.');
    }

    public function edit(Service $service): View
    {
        abort_unless($service->user_id === auth()->id(), 403);
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        abort_unless($service->user_id === auth()->id(), 403);

        if ($request->filled('url')) {
            $url = trim($request->input('url'));
            if (! preg_match('#^https?://#i', $url)) {
                $request->merge(['url' => 'https://' . $url]);
            }
        }

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'url'        => ['nullable', 'url', 'max:500'],
            'account_id' => ['nullable', 'string', 'max:255'],
            'password'   => ['required', 'string'],
            'notes'      => ['nullable', 'string', 'max:5000'],
        ]);

        $service->update($data);

        return redirect()->route('dashboard')->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        abort_unless($service->user_id === auth()->id(), 403);
        $service->delete();

        return redirect()->route('dashboard')->with('success', 'Service supprimé.');
    }
}
