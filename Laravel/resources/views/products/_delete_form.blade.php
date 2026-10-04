<form method="POST" action="{{ route('products.destroy', $product) }}" style="display: inline" onsubmit="return confirm('Видалити цей товар?');">
    @csrf
    @method('DELETE')
    <button type="submit">Видалити</button>
</form>
