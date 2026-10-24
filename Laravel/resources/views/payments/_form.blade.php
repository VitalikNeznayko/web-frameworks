@csrf

<div class="form-grid">
    <div class="field">
        <label for="booking_id">Бронювання</label>
        <select id="booking_id" name="booking_id" class="@error('booking_id') is-invalid @enderror" required>
            <option value="">— оберіть бронювання —</option>
            @foreach ($bookings as $booking)
                <option value="{{ $booking->id }}" @selected((string) old('booking_id', $payment->booking_id) === (string) $booking->id)>
                    #{{ $booking->id }}: {{ $booking->guest->full_name }}, {{ $booking->check_in->format('d.m.Y') }} – {{ $booking->check_out->format('d.m.Y') }}
                </option>
            @endforeach
        </select>
        @error('booking_id') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="amount">Сума, грн</label>
        <input type="number" id="amount" name="amount" min="0.01" step="0.01" value="{{ old('amount', $payment->amount) }}" class="@error('amount') is-invalid @enderror" required>
        @error('amount') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="method">Спосіб оплати</label>
        <select id="method" name="method" class="@error('method') is-invalid @enderror" required>
            @foreach (\App\Models\Payment::METHODS as $value => $label)
                <option value="{{ $value }}" @selected(old('method', $payment->method) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('method') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="status">Статус</label>
        <select id="status" name="status" class="@error('status') is-invalid @enderror" required>
            @foreach (\App\Models\Payment::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $payment->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="paid_at">Дата оплати</label>
        <input type="datetime-local" id="paid_at" name="paid_at" value="{{ old('paid_at', $payment->paid_at?->format('Y-m-d\TH:i')) }}" class="@error('paid_at') is-invalid @enderror">
        @error('paid_at') <span class="error-text">{{ $message }}</span> @enderror
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn">Зберегти</button>
    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Скасувати</a>
</div>
