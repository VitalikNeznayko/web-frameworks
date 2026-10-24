<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Хостел') | Система управління хостелом</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background: #f4f6f9; color: #1f2933; }
        header { background: #1f3a5f; color: #fff; }
        header .bar { max-width: 1100px; margin: 0 auto; padding: 14px 20px; display: flex; flex-wrap: wrap; align-items: center; gap: 18px; }
        header a.brand { color: #fff; font-weight: 700; font-size: 18px; text-decoration: none; margin-right: auto; }
        header nav a { color: #d6e4f5; text-decoration: none; padding: 6px 10px; border-radius: 4px; }
        header nav a:hover, header nav a.active { background: rgba(255,255,255,.15); color: #fff; }
        main { max-width: 1100px; margin: 24px auto; padding: 0 20px; }
        h1 { margin: 0 0 16px; font-size: 26px; }
        h2 { font-size: 20px; margin: 28px 0 12px; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.08); padding: 20px; margin-bottom: 20px; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; flex-wrap: wrap; }
        .toolbar h1 { margin: 0; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e4e7eb; text-align: left; vertical-align: top; }
        th { background: #eef2f7; font-weight: 600; font-size: 14px; }
        tr:hover td { background: #fafbfc; }
        .actions { display: flex; gap: 6px; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 7px 14px; border-radius: 5px; border: 1px solid transparent; background: #2f6fb3; color: #fff; text-decoration: none; font-size: 14px; cursor: pointer; line-height: 1.3; }
        .btn:hover { background: #255a92; }
        .btn-secondary { background: #fff; color: #2f6fb3; border-color: #2f6fb3; }
        .btn-secondary:hover { background: #eef4fb; }
        .btn-danger { background: #c0392b; }
        .btn-danger:hover { background: #a03024; }
        .btn-sm { padding: 4px 10px; font-size: 13px; }
        form.inline { display: inline; margin: 0; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
        .alert-success { background: #e3f6e8; color: #1e6b34; border: 1px solid #b8e4c4; }
        .alert-error { background: #fdecea; color: #922b21; border: 1px solid #f5c6c0; }
        .alert ul { margin: 6px 0 0; padding-left: 20px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
        .field { display: flex; flex-direction: column; gap: 6px; }
        .field.full { grid-column: 1 / -1; }
        label { font-weight: 600; font-size: 14px; }
        input[type=text], input[type=email], input[type=password], input[type=number], input[type=date], input[type=datetime-local], select, textarea { padding: 8px 10px; border: 1px solid #c3cbd5; border-radius: 5px; font: inherit; width: 100%; background: #fff; }
        textarea { min-height: 90px; resize: vertical; }
        .is-invalid { border-color: #c0392b !important; }
        .error-text { color: #c0392b; font-size: 13px; }
        .checkboxes { display: flex; flex-wrap: wrap; gap: 10px 20px; }
        .checkboxes label { font-weight: 400; display: flex; align-items: center; gap: 6px; }
        .form-actions { margin-top: 20px; display: flex; gap: 10px; }
        dl.details { display: grid; grid-template-columns: 200px 1fr; gap: 8px 16px; margin: 0; }
        dl.details dt { font-weight: 600; color: #52606d; }
        dl.details dd { margin: 0; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; background: #e4e7eb; font-size: 12px; margin: 1px 2px 1px 0; }
        .badge-green { background: #d4f0dc; color: #1e6b34; }
        .badge-red { background: #f8d7d3; color: #922b21; }
        .muted { color: #7b8794; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .stat { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); text-decoration: none; color: inherit; display: block; }
        .stat:hover { box-shadow: 0 3px 10px rgba(0,0,0,.12); }
        .stat .num { font-size: 32px; font-weight: 700; color: #1f3a5f; }
        .stat .lbl { color: #52606d; margin-top: 4px; }
        .pagination { display: flex; flex-wrap: wrap; gap: 4px; list-style: none; padding: 0; margin: 16px 0 0; }
        .pagination li a, .pagination li span { display: block; padding: 6px 11px; border: 1px solid #c3cbd5; border-radius: 4px; background: #fff; color: #2f6fb3; text-decoration: none; font-size: 14px; }
        .pagination li.active span { background: #2f6fb3; color: #fff; border-color: #2f6fb3; }
        .pagination li.disabled span { color: #9aa5b1; }
        .filters { padding: 16px; }
        .filter-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 10px 14px; }
        .filter-grid .field { gap: 4px; }
        .filter-grid label { font-size: 13px; color: #52606d; }
        .filter-grid input, .filter-grid select { padding: 6px 8px; font-size: 14px; }
        .filter-grid .range { display: flex; flex-wrap: wrap; gap: 6px; }
        .filter-grid .range input { flex: 1 1 100px; min-width: 0; }
        .filter-actions { margin-top: 14px; display: flex; gap: 10px; flex-wrap: wrap; }
        .filters .error-text { margin: 12px 0 0; }
        .pager { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-top: 16px; }
        .pager .pagination { margin: 0; }
        .pager-info { color: #52606d; font-size: 14px; }
        .per-page { display: flex; align-items: center; gap: 8px; margin: 0; }
        .per-page label { font-weight: 400; color: #52606d; white-space: nowrap; }
        .per-page select { width: auto; padding: 5px 8px; }
        .user-box { display: flex; align-items: center; gap: 10px; }
        .user-box .user-name { color: #fff; font-weight: 600; }
        .role-badge { margin: 0; font-weight: 600; }
        .role-admin { background: #f8d7d3; color: #922b21; }
        .role-manager { background: #fdebc8; color: #8a5a00; }
        .role-client { background: #d4f0dc; color: #1e6b34; }
        .auth-card { max-width: 440px; margin: 40px auto; }
        .auth-card h1 { text-align: center; }
        .auth-card .form-grid { grid-template-columns: 1fr; }
        .auth-card .form-actions { justify-content: space-between; align-items: center; }
        .error-page { text-align: center; padding: 40px 20px; }
        .error-page .form-actions { justify-content: center; }
        .error-page .code { font-size: 64px; font-weight: 700; color: #c0392b; margin: 0; }
        @media (max-width: 640px) { dl.details { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<header>
    <div class="bar">
        <a href="{{ route('home') }}" class="brand">Хостел</a>
        @auth
            <nav>
                @can('viewAny', App\Models\Room::class)
                    <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.*') ? 'active' : '' }}">Кімнати</a>
                @endcan
                @can('viewAny', App\Models\Amenity::class)
                    <a href="{{ route('amenities.index') }}" class="{{ request()->routeIs('amenities.*') ? 'active' : '' }}">Зручності</a>
                @endcan
                @can('viewAny', App\Models\Bed::class)
                    <a href="{{ route('beds.index') }}" class="{{ request()->routeIs('beds.*') ? 'active' : '' }}">Ліжка</a>
                @endcan
                @can('viewAny', App\Models\Guest::class)
                    <a href="{{ route('guests.index') }}" class="{{ request()->routeIs('guests.*') ? 'active' : '' }}">Гості</a>
                @endcan
                @can('viewAny', App\Models\Booking::class)
                    <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">Бронювання</a>
                @endcan
                @can('viewAny', App\Models\Payment::class)
                    <a href="{{ route('payments.index') }}" class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">Платежі</a>
                @endcan
                @can('viewAny', App\Models\User::class)
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">Користувачі</a>
                @endcan
            </nav>
            <div class="user-box">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="badge role-badge role-{{ auth()->user()->role }}">{{ auth()->user()->roleLabel() }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">Вийти</button>
                </form>
            </div>
        @else
            <nav>
                <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Вхід</a>
                <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">Реєстрація</a>
            </nav>
        @endauth
    </div>
</header>
<main>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error">
            Будь ласка, виправте помилки у формі:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>
