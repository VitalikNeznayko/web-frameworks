<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Bed;
use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::with(['guest', 'bed.room'])
            ->withSum('payments', 'amount')
            ->orderByDesc('check_in')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(): View
    {
        return view('bookings.create', array_merge(
            ['booking' => new Booking(['status' => 'pending'])],
            $this->formOptions(),
        ));
    }

    public function store(BookingRequest $request): RedirectResponse
    {
        $booking = Booking::create($request->validated());

        return redirect()->route('bookings.show', $booking)->with('success', 'Бронювання створено.');
    }

    public function show(Booking $booking): View
    {
        $booking->load(['guest', 'bed.room', 'payments' => fn ($query) => $query->orderByDesc('created_at')]);

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        return view('bookings.edit', array_merge(['booking' => $booking], $this->formOptions()));
    }

    public function update(BookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());

        return redirect()->route('bookings.show', $booking)->with('success', 'Бронювання оновлено.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Бронювання видалено.');
    }

    private function formOptions(): array
    {
        return [
            'guests' => Guest::orderBy('last_name')->orderBy('first_name')->get(),
            'beds' => Bed::with('room')->orderBy('room_id')->orderBy('label')->get(),
        ];
    }
}
