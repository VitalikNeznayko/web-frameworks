@csrf

<div class="form-grid">
    <div class="field">
        <label for="name">Ім'я</label>
        <input type="text" id="name" name="name" maxlength="255" value="{{ old('name', $user->name) }}" class="@error('name') is-invalid @enderror" required>
        @error('name') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" maxlength="255" value="{{ old('email', $user->email) }}" class="@error('email') is-invalid @enderror" required>
        @error('email') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="role">Роль</label>
        @if ($user->exists && auth()->user()->cannot('changeRole', $user))
            <input type="hidden" name="role" value="{{ $user->role }}">
            <select id="role" disabled>
                <option>{{ $user->roleLabel() }}</option>
            </select>
            <span class="muted">Ви не можете змінити власну роль.</span>
        @else
            <select id="role" name="role" class="@error('role') is-invalid @enderror" required>
                <option value="">— оберіть —</option>
                @foreach (\App\Models\User::ROLES as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        @endif
        @error('role') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="password">{{ $user->exists ? 'Новий пароль' : 'Пароль' }}</label>
        <input type="password" id="password" name="password" minlength="8" class="@error('password') is-invalid @enderror" @required(! $user->exists) autocomplete="new-password">
        @if ($user->exists)
            <span class="muted">Залиште порожнім, щоб не змінювати.</span>
        @endif
        @error('password') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="password_confirmation">Підтвердження пароля</label>
        <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" @required(! $user->exists) autocomplete="new-password">
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn">Зберегти</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Скасувати</a>
</div>
