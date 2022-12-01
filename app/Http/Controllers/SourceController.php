<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SourceController extends Controller
{
    public function index()
    {
        $sources = Source::all();
        return Inertia::render('Sources/Index', ['sources' => $sources]);
    }

    public function create()
    {
        return Inertia::render('Sources/Create');
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

        Source::create([
            'name' => request('name'),
            'color' => request('color'),
        ]);

        return redirect()->route('sources.index');
    }

    public function delete(Source $category)
    {
        $category = Source::findOrFail($category->id);

        $category->delete();

        return redirect()->route('sources.index');
    }
}
