<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expense::all()->where('user_id', Auth::user()->id);

        return inertia('Expenses/Index', ['expenses' => $expenses]);
    }
}
