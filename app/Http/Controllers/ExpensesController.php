<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::user()->id)->get();

        return inertia('Expenses/Index', ['expenses' => $expenses]);
    }

    public function create()
    {
    }

    public function store()
    {
        $this->validate(request(), [
            'date' => ['required', 'date'],
        ]);

        Expense::create([
            'user_id' => Auth::user()->id,
            'date' => Carbon::parse(request('date')),
            'cost' => (int) str_replace('.', '', request('cost')) * 100,
            'description' => request('description'),
            'observation' => request('observation'),
        ]);

        return redirect()->route('expenses.index');
    }
}
