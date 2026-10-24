@csrf

<div class="form-grid">
    <div class="field">
        <label for="name">Назва</label>
        <input type="text" id="name" name="name" maxlength="100" value="{{ old('name', $amenity->name) }}" class="@error('name') is-invalid @enderror" required>
        @error('name') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="description">Опис</label>
        <input type="text" id="description" name="description" maxlength="255" value="{{ old('description', $amenity->description) }}" class="@error('description') is-invalid @enderror">
        @error('description') <span class="error-text">{{ $message }}</span> @enderror
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn">Зберегти</button>
    <a href="{{ route('amenities.index') }}" class="btn btn-secondary">Скасувати</a>
</div>
