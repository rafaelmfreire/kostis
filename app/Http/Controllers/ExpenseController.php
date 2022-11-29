<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function index()
    {
        $month = request('month') ?? Carbon::create(Carbon::now()->format('Y'), Carbon::now()->format('m'), 1);
        $start_date = new Carbon($month.'-01');

        $expenses = Expense::where(
            'user_id', Auth::user()->id
        )->where(
            'date', '>=', $start_date->format('Y-m-d')
        )->where(
            'date', '<', $start_date->add('month', 1)->format('Y-m-d')
        )->get();

        return inertia('Expenses/Index', ['expenses' => $expenses]);
    }

    public function create()
    {
        return Inertia::render('Expenses/Create');
    }

    public function store()
    {
        $this->validate(request(), [
            'date' => ['required', 'date'],
            'cost' => ['required', 'numeric'],
            'description' => ['required'],
        ]);

        Auth::user()->expenses()->create([
            'user_id' => Auth::user()->id,
            'date' => Carbon::parse(request('date')),
            'cost' => request('cost') * 100,
            'description' => request('description'),
            'observation' => request('observation'),
        ]);

        if (request('addNew')) {
            return redirect()->route('expenses.create');
        }

        return redirect()->route('expenses.index');
    }

    public function delete(Expense $expense)
    {
        $expense = Auth::user()->expenses()->findOrFail($expense->id);

        $month = $expense->date->format('Y-m');

        $expense->delete();

        return redirect()->route('expenses.index', ['month' => $month]);
    }
}
