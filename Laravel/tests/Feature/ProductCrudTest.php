<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index shows products', function () {
    Product::create(['name' => 'Ноутбук', 'price' => 25000]);

    $this->get(route('products.index'))
        ->assertOk()
        ->assertSee('Ноутбук');
});

test('product can be created', function () {
    $this->post(route('products.store'), [
        'name' => 'Мишка',
        'description' => 'Бездротова',
        'price' => '499.99',
    ])->assertRedirect(route('products.index'));

    $this->assertDatabaseHas('products', ['name' => 'Мишка', 'price' => 499.99]);
});

test('invalid product is rejected', function () {
    $this->post(route('products.store'), ['name' => '', 'price' => -5])
        ->assertSessionHasErrors(['name', 'price']);

    $this->assertDatabaseCount('products', 0);
});

test('product can be shown', function () {
    $product = Product::create(['name' => 'Монітор', 'price' => 7000]);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('Монітор');
});

test('product can be updated', function () {
    $product = Product::create(['name' => 'Стара назва', 'price' => 100]);

    $this->put(route('products.update', $product), [
        'name' => 'Нова назва',
        'price' => 150,
    ])->assertRedirect(route('products.index'));

    expect($product->fresh()->name)->toBe('Нова назва');
});

test('product can be deleted', function () {
    $product = Product::create(['name' => 'Клавіатура', 'price' => 1200]);

    $this->delete(route('products.destroy', $product))
        ->assertRedirect(route('products.index'));

    $this->assertModelMissing($product);
});

test('missing product returns 404', function () {
    $this->get(route('products.show', 999))->assertNotFound();
});
