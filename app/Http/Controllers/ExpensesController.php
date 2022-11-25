<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::user()->id)->get();

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

        return redirect()->route('expenses.index');
    }

    public function delete(Expense $expense)
    {
        $expense = Auth::user()->expenses()->findOrFail($expense->id);

        $expense->delete();
        return redirect()->route('expenses.index');
    }
}
