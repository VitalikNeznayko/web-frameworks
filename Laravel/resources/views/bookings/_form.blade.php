@csrf

<div class="form-grid">
    <div class="field">
        <label for="guest_id">Гість</label>
        <select id="guest_id" name="guest_id" class="@error('guest_id') is-invalid @enderror" required>
            <option value="">— оберіть гостя —</option>
            @foreach ($guests as $guest)
                <option value="{{ $guest->id }}" @selected((string) old('guest_id', $booking->guest_id) === (string) $guest->id)>
                    {{ $guest->full_name }} ({{ $guest->email }})
                </option>
            @endforeach
        </select>
        @error('guest_id') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="bed_id">Ліжко</label>
        <select id="bed_id" name="bed_id" class="@error('bed_id') is-invalid @enderror" required>
            <option value="">— оберіть ліжко —</option>
            @foreach ($beds as $bed)
                <option value="{{ $bed->id }}" @selected((string) old('bed_id', $booking->bed_id) === (string) $bed->id)>
                    Кімната {{ $bed->room->number }}, ліжко {{ $bed->label }} ({{ $bed->typeLabel() }}){{ $bed->is_active ? '' : ', неактивне' }}
                </option>
            @endforeach
        </select>
        @error('bed_id') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="check_in">Дата заїзду</label>
        <input type="date" id="check_in" name="check_in" value="{{ old('check_in', $booking->check_in?->format('Y-m-d')) }}" class="@error('check_in') is-invalid @enderror" required>
        @error('check_in') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="check_out">Дата виїзду</label>
        <input type="date" id="check_out" name="check_out" value="{{ old('check_out', $booking->check_out?->format('Y-m-d')) }}" class="@error('check_out') is-invalid @enderror" required>
        @error('check_out') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="status">Статус</label>
        <select id="status" name="status" class="@error('status') is-invalid @enderror" required>
            @foreach (\App\Models\Booking::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $booking->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="total_price">Загальна вартість, грн</label>
        <input type="number" id="total_price" name="total_price" min="0" step="0.01" value="{{ old('total_price', $booking->total_price) }}" class="@error('total_price') is-invalid @enderror" required>
        @error('total_price') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field full">
        <label for="notes">Примітки</label>
        <textarea id="notes" name="notes" class="@error('notes') is-invalid @enderror">{{ old('notes', $booking->notes) }}</textarea>
        @error('notes') <span class="error-text">{{ $message }}</span> @enderror
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn">Зберегти</button>
    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Скасувати</a>
</div>
