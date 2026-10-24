@csrf

<div class="form-grid">
    <div class="field">
        <label for="first_name">Ім'я</label>
        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $guest->first_name) }}" class="@error('first_name') is-invalid @enderror" required>
        @error('first_name') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="last_name">Прізвище</label>
        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $guest->last_name) }}" class="@error('last_name') is-invalid @enderror" required>
        @error('last_name') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $guest->email) }}" class="@error('email') is-invalid @enderror" required>
        @error('email') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="phone">Телефон</label>
        <input type="text" id="phone" name="phone" maxlength="30" value="{{ old('phone', $guest->phone) }}" class="@error('phone') is-invalid @enderror" required>
        @error('phone') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="passport_number">Номер паспорта</label>
        <input type="text" id="passport_number" name="passport_number" maxlength="50" value="{{ old('passport_number', $guest->passport_number) }}" class="@error('passport_number') is-invalid @enderror" required>
        @error('passport_number') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="country">Країна</label>
        <input type="text" id="country" name="country" maxlength="100" value="{{ old('country', $guest->country) }}" class="@error('country') is-invalid @enderror" required>
        @error('country') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="birth_date">Дата народження</label>
        <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $guest->birth_date?->format('Y-m-d')) }}" class="@error('birth_date') is-invalid @enderror">
        @error('birth_date') <span class="error-text">{{ $message }}</span> @enderror
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn">Зберегти</button>
    <a href="{{ route('guests.index') }}" class="btn btn-secondary">Скасувати</a>
</div>
