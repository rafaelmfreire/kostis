<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Installment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $installments = Installment::join(
            'expenses', 'expenses.id', '=', 'installments.expense_id'
        )->join(
            'categories', 'categories.id', '=', 'expenses.category_id'
        )->join(
            'sources', 'sources.id', '=', 'expenses.source_id'
        )->select(
            DB::raw('sum(installments.cost) as total_cost, categories.id as category_id, YEAR(installments.paid_at) as year, MONTH(installments.paid_at) as month')
        )->groupByRaw(
            'categories.id, installments.paid_at, EXTRACT(YEAR_MONTH FROM installments.paid_at)'
        )->havingBetween(
            'paid_at', [Carbon::now()->format('Y').'-01-01', Carbon::now()->format('Y').'-12-31']
        )->get()->map(function ($installment) {
            return [
                'category_id' => $installment->category_id,
                'cost_by_month' => number_format($installment->total_cost / 100, 2, ',', '.'),
                'year' => $installment->year,
                'month' => $installment->month
            ];
        });

        return Inertia::render('Dashboard', [
            'installments' => $installments,
            'categories' => $categories,
        ]);
    }
}
