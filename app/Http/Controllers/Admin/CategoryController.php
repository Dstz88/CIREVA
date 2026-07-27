<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\eventCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = eventCategory::with(['events.organizerProfile.user'])->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function show(eventCategory $category)
    {
        $category->load(['events.organizerProfile.user', 'events.location']);
        return view('admin.categories.show', compact('category'));
    }
}
