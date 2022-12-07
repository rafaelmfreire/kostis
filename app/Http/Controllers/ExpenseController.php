<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Installment;
use App\Models\Source;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function index()
    {
        $month = request('month') ?? Carbon::create(Carbon::now()->format('Y'), Carbon::now()->format('m'), 1);
        $start_date = new CarbonImmutable($month.'-01');
        $end_date = $start_date->add('month', 1)->format('Y-m-d');

        $installments = Installment::with([ 'expense', 'expense.source', 'expense.category' ])
        ->whereHas('expense', function (Builder $query) {
            $query->where('user_id', Auth::user()->id);
        })->where(
            'paid_at', '>=', $start_date->format('Y-m-d')
        )->where(
            'paid_at', '<', $end_date
        )->join(
            'expenses', 'expenses.id', '=', 'installments.expense_id'
        )->orderBy(
            'expenses.bought_at', 'DESC'
        )->get()->map(function ($installment) {
            return [
                'id' => $installment->expense->id,
                'user_id' => $installment->expense->user_id,
                // 'category_id' => $installment->expense->category_id,
                // 'source_id' => $installment->expense->source_id,
                'installments_quantity' => $installment->expense->installments_quantity,
                'number' => $installment->number,
                'cost' => $installment->cost,
                'formatted_cost' => $installment->formatted_cost,
                // 'bought_at' => $installment->expense->bought_at,
                'formatted_bought_at' => $installment->expense->formatted_bought_at,
                // 'paid_at' => $installment->paid_at,
                'formatted_paid_at' => $installment->formatted_paid_at,
                'description' => $installment->expense->description,
                'observation' => $installment->expense->observation,
                'category_name' => $installment->expense->category->name,
                'category_color' => $installment->expense->category->color,
                'source_name' => $installment->expense->source->name,
                'source_color' => $installment->expense->source->color,
            ];
        });

        return inertia('Expenses/Index', [
            'expenses' => $installments,
            'stats' => [
                'total_cost' => number_format($installments->sum('cost') / 100, 2, ',', '.'),
                'most_expensive' => number_format($installments->max('cost') / 100, 2, ',', '.'),
                'expenses_quantity' => $installments->count(),
                'average' => number_format(($installments->sum('cost') / ($installments->count() == 0 ? 1 : $installments->count())) / 100, 2, ',', '.'),
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
            'paid_at' => ['required', 'date_format:Y-m'],
            'cost' => ['required', 'numeric'],
            'description' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'source_id' => ['required', 'exists:sources,id'],
        ]);

        $expense = Auth::user()->expenses()->create([
            'user_id' => Auth::user()->id,
            'category_id' => request('category_id'),
            'source_id' => request('source_id'),
            'bought_at' => Carbon::parse(request('bought_at')),
            'cost' => request('cost') * 100,
            'installments_quantity' => request('installments_quantity'),
            'description' => request('description'),
            'observation' => request('observation'),
        ]);

        $expense->addInstallments(request('paid_at'));

        if (request('addNew')) {
            return redirect()->route('expenses.create');
        }

        return redirect()->route('expenses.index');
    }

    public function edit(Expense $expense)
    {
        $expense = Auth::user()->expenses()->findOrFail($expense->id);

        $categories = Category::all();
        $sources = Source::all();

        return Inertia::render('Expenses/Edit', ['categories' => $categories, 'sources' => $sources, 'expense' => $expense]);
    }

    public function update(Expense $expense)
    {
        $expense = Auth::user()->expenses()->findOrFail($expense->id);
        
        $this->validate(request(), [
            'bought_at' => ['required', 'date'],
            // 'paid_at' => ['required', 'date'],
            // 'cost' => ['required', 'numeric'],
            'description' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'source_id' => ['required', 'exists:sources,id'],
        ]);

        $expense->update([
            'category_id' => request('category_id'),
            'source_id' => request('source_id'),
            'bought_at' => Carbon::parse(request('bought_at')),
            // 'paid_at' => Carbon::parse(request('paid_at')),
            // 'installments_quantity' => request('installments_quantity'),
            // 'cost' => request('cost') * 100,
            'description' => request('description'),
            'observation' => request('observation'),
        ]);

        return redirect()->route('expenses.index');
    }

    public function delete(Expense $expense)
    {
        $expense = Auth::user()->expenses()->findOrFail($expense->id);

        $month = $expense->installments()->first()->paid_at->format('Y-m');

        $expense->delete();

        return redirect()->route('expenses.index', ['month' => $month]);
    }
}
