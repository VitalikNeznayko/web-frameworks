<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * READ — список усіх записів
     */
    public function index(): View
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * CREATE — форма створення
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * CREATE — збереження нового запису
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Товар створено.');
    }

    /**
     * READ — перегляд одного запису (Product знаходиться за {product} автоматично)
     */
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * UPDATE — форма редагування
     */
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    /**
     * UPDATE — збереження змін
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Товар оновлено.');
    }

    /**
     * DELETE — видалення запису
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Товар видалено.');
    }

    /**
     * Правила валідації для create та update
     */
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
