<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('Categories/Index', ['categories' => $categories]);
    }

    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    public function store()
    {
        $this->validate(request(), [
            'name' => ['required'],
            'color' => [
                'nullable',
                Rule::in([
                    'red',
                    'orange',
                    'amber',
                    'yellow',
                    'lime',
                    'green',
                    'emerald',
                    'teal',
                    'cyan',
                    'sky',
                    'blue',
                    'indigo',
                    'violet',
                    'purple',
                    'fuchsia',
                    'pink',
                    'rose'
                ])
            ]
        ]);

        Category::create([
            'name' => request('name'),
            'color' => request('color'),
        ]);

        return redirect()->route('categories.index');
    }

    public function delete(Category $category)
    {
        $category = Category::findOrFail($category->id);

        $category->delete();

        return redirect()->route('categories.index');
    }
}
