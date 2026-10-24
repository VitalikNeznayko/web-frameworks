<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        $rooms = Room::with('amenities')
            ->withCount('beds')
            ->orderBy('number')
            ->paginate(10);

        return view('rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        return view('rooms.create', [
            'room' => new Room(),
            'amenities' => Amenity::orderBy('name')->get(),
            'selectedAmenities' => [],
        ]);
    }

    public function store(RoomRequest $request): RedirectResponse
    {
        $room = DB::transaction(function () use ($request) {
            $room = Room::create($request->safe()->except('amenities'));
            $room->amenities()->sync($request->validated('amenities', []));

            return $room;
        });

        return redirect()->route('rooms.show', $room)->with('success', 'Кімнату створено.');
    }

    public function show(Room $room): View
    {
        $room->load(['beds' => fn ($query) => $query->orderBy('label'), 'amenities']);

        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room): View
    {
        return view('rooms.edit', [
            'room' => $room,
            'amenities' => Amenity::orderBy('name')->get(),
            'selectedAmenities' => $room->amenities()->pluck('amenities.id')->all(),
        ]);
    }

    public function update(RoomRequest $request, Room $room): RedirectResponse
    {
        DB::transaction(function () use ($request, $room) {
            $room->update($request->safe()->except('amenities'));
            $room->amenities()->sync($request->validated('amenities', []));
        });

        return redirect()->route('rooms.show', $room)->with('success', 'Кімнату оновлено.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $hasBookings = Booking::whereIn('bed_id', $room->beds()->select('id'))->exists();

        if ($hasBookings) {
            return back()->with('error', 'Неможливо видалити кімнату: її ліжка мають бронювання.');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Кімнату видалено.');
    }
}
