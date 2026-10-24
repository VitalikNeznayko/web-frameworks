@csrf

<div class="form-grid">
    <div class="field">
        <label for="number">Номер кімнати</label>
        <input type="text" id="number" name="number" maxlength="10" value="{{ old('number', $room->number) }}" class="@error('number') is-invalid @enderror" required>
        @error('number') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="type">Тип</label>
        <select id="type" name="type" class="@error('type') is-invalid @enderror" required>
            <option value="">— оберіть —</option>
            @foreach (\App\Models\Room::TYPES as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $room->type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="floor">Поверх</label>
        <input type="number" id="floor" name="floor" min="0" max="200" value="{{ old('floor', $room->floor) }}" class="@error('floor') is-invalid @enderror" required>
        @error('floor') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="price_per_night">Ціна за ніч, грн</label>
        <input type="number" id="price_per_night" name="price_per_night" min="0" step="0.01" value="{{ old('price_per_night', $room->price_per_night) }}" class="@error('price_per_night') is-invalid @enderror" required>
        @error('price_per_night') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field full">
        <label for="description">Опис</label>
        <textarea id="description" name="description" class="@error('description') is-invalid @enderror">{{ old('description', $room->description) }}</textarea>
        @error('description') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field full">
        <label>Зручності</label>
        @php $checked = array_map('intval', old('amenities', $selectedAmenities)); @endphp
        @if ($amenities->isEmpty())
            <span class="muted">Зручностей ще немає. <a href="{{ route('amenities.create') }}">Додати зручність</a></span>
        @else
            <div class="checkboxes">
                @foreach ($amenities as $amenity)
                    <label>
                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" @checked(in_array($amenity->id, $checked, true))>
                        {{ $amenity->name }}
                    </label>
                @endforeach
            </div>
        @endif
        @error('amenities') <span class="error-text">{{ $message }}</span> @enderror
        @error('amenities.*') <span class="error-text">{{ $message }}</span> @enderror
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn">Зберегти</button>
    <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Скасувати</a>
</div>
