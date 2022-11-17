<?php

namespace App\Http\Controllers;

use App\Models\Expense;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expense::all();

        return inertia('Expenses/Index', ['expenses' => $expenses]);
    }
}
