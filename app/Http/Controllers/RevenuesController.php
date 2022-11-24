<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RevenuesController extends Controller
{
    //
    public function create()
    {
    }

    public function store()
    {
        $this->validate(request(), [
            'date' => ['required', 'date'],
            'income' => ['required', 'numeric'],
            'description' => ['required'],
        ]);

        Auth::user()->revenues()->create([
            'user_id' => Auth::user()->id,
            'date' => Carbon::parse(request('date')),
            'income' => request('income') * 100,
            'description' => request('description'),
            'observation' => request('observation'),
        ]);

        return redirect()->route('revenues.index');
    }
}
