<?php

namespace App\Http\Controllers;

use App\Filters\PaymentFilter;
use App\Http\Requests\PaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\View\View;

class PaymentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return self::resourceAuthorization(Payment::class, 'payment');
    }

    public function index(Request $request): View
    {
        $filter = new PaymentFilter($request->query());

        $payments = $this->paginate(
            Payment::filter($filter)->with('booking.guest')->orderByDesc('created_at'),
            $request,
        );

        return view('payments.index', compact('payments', 'filter'));
    }

    public function create(): View
    {
        return view('payments.create', [
            'payment' => new Payment(['status' => 'pending', 'method' => 'cash']),
            'bookings' => $this->bookingOptions(),
        ]);
    }

    public function store(PaymentRequest $request): RedirectResponse
    {
        $payment = Payment::create($request->validated());

        return redirect()->route('payments.show', $payment)->with('success', 'Платіж створено.');
    }

    public function show(Payment $payment): View
    {
        $payment->load('booking.guest', 'booking.bed.room');

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment): View
    {
        return view('payments.edit', [
            'payment' => $payment,
            'bookings' => $this->bookingOptions(),
        ]);
    }

    public function update(PaymentRequest $request, Payment $payment): RedirectResponse
    {
        $payment->update($request->validated());

        return redirect()->route('payments.show', $payment)->with('success', 'Платіж оновлено.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Платіж видалено.');
    }

    private function bookingOptions(): Collection
    {
        return Booking::with('guest')->orderByDesc('check_in')->get();
    }
}
