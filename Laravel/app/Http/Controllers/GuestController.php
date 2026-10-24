<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuestRequest;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GuestController extends Controller
{
    public function index(): View
    {
        $guests = Guest::withCount('bookings')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(10);

        return view('guests.index', compact('guests'));
    }

    public function create(): View
    {
        return view('guests.create', ['guest' => new Guest()]);
    }

    public function store(GuestRequest $request): RedirectResponse
    {
        $guest = Guest::create($request->validated());

        return redirect()->route('guests.show', $guest)->with('success', 'Гостя створено.');
    }

    public function show(Guest $guest): View
    {
        $guest->load(['bookings' => fn ($query) => $query->with('bed.room')->orderByDesc('check_in')]);

        return view('guests.show', compact('guest'));
    }

    public function edit(Guest $guest): View
    {
        return view('guests.edit', compact('guest'));
    }

    public function update(GuestRequest $request, Guest $guest): RedirectResponse
    {
        $guest->update($request->validated());

        return redirect()->route('guests.show', $guest)->with('success', 'Дані гостя оновлено.');
    }

    public function destroy(Guest $guest): RedirectResponse
    {
        if ($guest->bookings()->exists()) {
            return back()->with('error', 'Неможливо видалити гостя: він має бронювання.');
        }

        $guest->delete();

        return redirect()->route('guests.index')->with('success', 'Гостя видалено.');
    }
}
