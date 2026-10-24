<?php

namespace App\Http\Controllers;

use App\Http\Requests\BedRequest;
use App\Models\Bed;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BedController extends Controller
{
    public function index(): View
    {
        $beds = Bed::with('room')
            ->withCount('bookings')
            ->orderBy('room_id')
            ->orderBy('label')
            ->paginate(10);

        return view('beds.index', compact('beds'));
    }

    public function create(): View
    {
        return view('beds.create', [
            'bed' => new Bed(['is_active' => true]),
            'rooms' => Room::orderBy('number')->get(),
        ]);
    }

    public function store(BedRequest $request): RedirectResponse
    {
        $bed = Bed::create($request->validated());

        return redirect()->route('beds.show', $bed)->with('success', 'Ліжко створено.');
    }

    public function show(Bed $bed): View
    {
        $bed->load(['room', 'bookings' => fn ($query) => $query->with('guest')->orderByDesc('check_in')]);

        return view('beds.show', compact('bed'));
    }

    public function edit(Bed $bed): View
    {
        return view('beds.edit', [
            'bed' => $bed,
            'rooms' => Room::orderBy('number')->get(),
        ]);
    }

    public function update(BedRequest $request, Bed $bed): RedirectResponse
    {
        $bed->update($request->validated());

        return redirect()->route('beds.show', $bed)->with('success', 'Ліжко оновлено.');
    }

    public function destroy(Bed $bed): RedirectResponse
    {
        if ($bed->bookings()->exists()) {
            return back()->with('error', 'Неможливо видалити ліжко: воно має бронювання.');
        }

        $bed->delete();

        return redirect()->route('beds.index')->with('success', 'Ліжко видалено.');
    }
}
