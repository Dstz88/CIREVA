<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\eventCategory;
use App\Models\eventLocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    /**
     * Display a listing of event Categories.
     */
    public function indexCategories(): View
    {
        $categories = eventCategory::latest()->paginate(10);
        return view('organizer.categories.index', compact('categories'));
    }

    /**
     * Display a listing of event Locations.
     */
    public function indexLocations(): View
    {
        $locations = eventLocation::latest()->paginate(10);
        return view('organizer.locations.index', compact('locations'));
    }

    /**
     * Store new event Category created by Organizer.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:event_categories,name'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah ada.',
        ]);

        $category = eventCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description ?? null,
        ]);

        return back()->with('success', 'Kategori baru "' . $category->name . '" berhasil ditambahkan!');
    }

    /**
     * Store new event Location created by Organizer.
     */
    public function storeLocation(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string'],
        ], [
            'name.required' => 'Nama lokasi wajib diisi.',
            'address.required' => 'Alamat lokasi wajib diisi.',
        ]);

        $location = eventLocation::create([
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude ?? -6.7320000,
            'longitude' => $request->longitude ?? 108.5520000,
            'capacity' => $request->capacity ?? 500,
        ]);

        return back()->with('success', 'Lokasi baru "' . $location->name . '" berhasil ditambahkan!');
    }
}
