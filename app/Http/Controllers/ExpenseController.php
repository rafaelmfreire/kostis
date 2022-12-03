<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Source;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function index()
    {
        $month = request('month') ?? Carbon::create(Carbon::now()->format('Y'), Carbon::now()->format('m'), 1);
        $start_date = new CarbonImmutable($month.'-01');
        $end_date = $start_date->add('month', 1)->format('Y-m-d');

        $expenses = Expense::with(['category', 'source'])->where(
            'user_id', Auth::user()->id
        )->where(
            'paid_at', '>=', $start_date->format('Y-m-d')
        )->where(
            'paid_at', '<', $end_date
        )->orderBy(
            'bought_at', 'desc'
        )->get()->map(function ($expense) {
            return [
                'id' => $expense->id,
                'user_id' => $expense->user_id,
                'category_id' => $expense->category_id,
                'source_id' => $expense->source_id,
                'cost' => $expense->cost,
                'formatted_cost' => $expense->formatted_cost,
                'bought_at' => $expense->bought_at,
                'formatted_bought_at' => $expense->formatted_bought_at,
                'paid_at' => $expense->paid_at,
                'formatted_paid_at' => $expense->formatted_paid_at,
                'description' => $expense->description,
                'observation' => $expense->observation,
                'category_name' => $expense->category->name,
                'category_color' => $expense->category->color,
                'source_name' => $expense->source->name,
                'source_color' => $expense->source->color,
            ];
        });

        return inertia('Expenses/Index', [
            'expenses' => $expenses,
            'stats' => [
                'total_cost' => number_format($expenses->sum('cost') / 100, 2, ',', '.'),
                'most_expensive' => number_format($expenses->max('cost') / 100, 2, ',', '.'),
                'expenses_quantity' => $expenses->count(),
                'average' => number_format(($expenses->sum('cost') / ($expenses->count() == 0 ? 1 : $expenses->count())) / 100, 2, ',', '.'),
            ],
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $sources = Source::all();

        return Inertia::render('Expenses/Create', ['categories' => $categories, 'sources' => $sources]);
    }

    public function store()
    {
        $this->validate(request(), [
            'bought_at' => ['required', 'date'],
            'paid_at' => ['required', 'date'],
            'cost' => ['required', 'numeric'],
            'description' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'source_id' => ['required', 'exists:sources,id'],
        ]);

        Auth::user()->expenses()->create([
            'user_id' => Auth::user()->id,
            'category_id' => request('category_id'),
            'source_id' => request('source_id'),
            'bought_at' => Carbon::parse(request('bought_at')),
            'paid_at' => Carbon::parse(request('paid_at')),
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

        $month = $expense->paid_at->format('Y-m');

        $expense->delete();

        return redirect()->route('expenses.index', ['month' => $month]);
    }
}
