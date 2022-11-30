<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

        $expenses = Expense::with('category')->where(
            'user_id', Auth::user()->id
        )->where(
            'date', '>=', $start_date->format('Y-m-d')
        )->where(
            'date', '<', $start_date->add('month', 1)->format('Y-m-d')
        )->orderBy(
            'date', 'desc'
        )->get()->map(function ($expense) {
            return [
                'id' => $expense->id,
                'user_id' => $expense->user_id,
                'category_id' => $expense->category_id,
                'cost' => $expense->cost,
                'formatted_cost' => $expense->formatted_cost,
                'date' => $expense->date,
                'formatted_date' => $expense->formatted_date,
                'description' => $expense->description,
                'observation' => $expense->observation,
                'category_name' => $expense->category->name,
                'category_color' => $expense->category->color,
            ];
        });

        return inertia('Expenses/Index', ['expenses' => $expenses]);
    }

    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Expenses/Create', ['categories' => $categories]);
    }

    public function store()
    {
        $this->validate(request(), [
            'date' => ['required', 'date'],
            'cost' => ['required', 'numeric'],
            'description' => ['required'],
            'category_id' => ['required', 'exists:categories,id']
        ]);

        Auth::user()->expenses()->create([
            'user_id' => Auth::user()->id,
            'category_id' => request('category_id'),
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
