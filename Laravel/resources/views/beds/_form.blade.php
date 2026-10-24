@csrf

<div class="form-grid">
    <div class="field">
        <label for="room_id">Кімната</label>
        <select id="room_id" name="room_id" class="@error('room_id') is-invalid @enderror" required>
            <option value="">— оберіть кімнату —</option>
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}" @selected((string) old('room_id', $bed->room_id) === (string) $room->id)>
                    {{ $room->number }} ({{ $room->typeLabel() }}, поверх {{ $room->floor }})
                </option>
            @endforeach
        </select>
        @error('room_id') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="label">Позначка</label>
        <input type="text" id="label" name="label" maxlength="10" value="{{ old('label', $bed->label) }}" class="@error('label') is-invalid @enderror" required>
        @error('label') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="bed_type">Тип ліжка</label>
        <select id="bed_type" name="bed_type" class="@error('bed_type') is-invalid @enderror" required>
            <option value="">— оберіть —</option>
            @foreach (\App\Models\Bed::TYPES as $value => $label)
                <option value="{{ $value }}" @selected(old('bed_type', $bed->bed_type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('bed_type') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label>Стан</label>
        <div class="checkboxes">
            <label>
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $bed->is_active))>
                Активне
            </label>
        </div>
        @error('is_active') <span class="error-text">{{ $message }}</span> @enderror
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn">Зберегти</button>
    <a href="{{ route('beds.index') }}" class="btn btn-secondary">Скасувати</a>
</div>
