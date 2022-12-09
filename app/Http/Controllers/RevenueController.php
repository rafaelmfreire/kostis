<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RevenueController extends Controller
{
    public function index()
    {
        $month = request('month') ?? Carbon::create(Carbon::now()->format('Y'), Carbon::now()->format('m'), 1);
        $start_date = new Carbon($month.'-01');

        $revenues = Revenue::where(
            'user_id', Auth::user()->id
        )->where(
            'date', '>=', $start_date->format('Y-m-d')
        )->where(
            'date', '<', $start_date->add('month', 1)->format('Y-m-d')
        )->orderBy(
            'date', 'desc'
        )->get();

        return Inertia::render('Revenues/Index', [
            'revenues' => $revenues,
            'stats' => [
                'total_income' => number_format($revenues->sum('income') / 100, 2, ',', '.'),
                'most_valuable' => number_format($revenues->max('income') / 100, 2, ',', '.'),
                'revenues_quantity' => $revenues->count(),
                'average' => number_format(($revenues->sum('income') / ($revenues->count() == 0 ? 1 : $revenues->count())) / 100, 2, ',', '.'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Revenues/Create');
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

    public function edit(Revenue $revenue)
    {
        $revenue = Auth::user()->revenues()->findOrFail($revenue->id);

        return Inertia::render('Revenues/Edit', ['revenue' => $revenue]);
    }

    public function update(Revenue $revenue)
    {

        $this->validate(request(), [
            'date' => ['required', 'date'],
            'income' => ['required', 'numeric'],
            'description' => ['required'],
        ]);

        $revenue->update([
            'date' => Carbon::parse(request('date')),
            'income' => request('income') * 100,
            'description' => request('description'),
            'observation' => request('observation'),
        ]);

        return redirect()->route('revenues.index');
    }

    public function delete(Revenue $revenue)
    {
        $revenue = Auth::user()->revenues()->findOrFail($revenue->id);

        $month = $revenue->date->format('Y-m');

        $revenue->delete();

        return redirect()->route('revenues.index', ['month' => $month]);
    }
}
