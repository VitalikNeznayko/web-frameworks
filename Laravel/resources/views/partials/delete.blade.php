<form action="{{ $action }}" method="POST" class="inline" onsubmit="return confirm('{{ $confirm ?? 'Видалити запис?' }}')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger {{ $size ?? 'btn-sm' }}">Видалити</button>
</form>
