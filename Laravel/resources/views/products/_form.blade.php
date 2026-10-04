@if ($errors->any())
    <ul style="color: red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<p>
    <label for="name">Назва</label><br>
    <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
</p>

<p>
    <label for="description">Опис</label><br>
    <textarea id="description" name="description">{{ old('description', $product->description ?? '') }}</textarea>
</p>

<p>
    <label for="price">Ціна, грн</label><br>
    <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" required>
</p>
