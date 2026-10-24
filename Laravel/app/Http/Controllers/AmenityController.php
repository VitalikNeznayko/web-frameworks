<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmenityRequest;
use App\Models\Amenity;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AmenityController extends Controller
{
    public function index(): View
    {
        $amenities = Amenity::withCount('rooms')->orderBy('name')->paginate(10);

        return view('amenities.index', compact('amenities'));
    }

    public function create(): View
    {
        return view('amenities.create', ['amenity' => new Amenity()]);
    }

    public function store(AmenityRequest $request): RedirectResponse
    {
        $amenity = Amenity::create($request->validated());

        return redirect()->route('amenities.show', $amenity)->with('success', 'Зручність створено.');
    }

    public function show(Amenity $amenity): View
    {
        $amenity->load(['rooms' => fn ($query) => $query->orderBy('number')]);

        return view('amenities.show', compact('amenity'));
    }

    public function edit(Amenity $amenity): View
    {
        return view('amenities.edit', compact('amenity'));
    }

    public function update(AmenityRequest $request, Amenity $amenity): RedirectResponse
    {
        $amenity->update($request->validated());

        return redirect()->route('amenities.show', $amenity)->with('success', 'Зручність оновлено.');
    }

    public function destroy(Amenity $amenity): RedirectResponse
    {
        $amenity->delete();

        return redirect()->route('amenities.index')->with('success', 'Зручність видалено.');
    }
}
