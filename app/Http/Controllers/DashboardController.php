<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Installment;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $year = request('year') ?? Carbon::now()->format('Y');

        $installments = Installment::join(
            'expenses', 'expenses.id', '=', 'installments.expense_id'
        )->join(
            'categories', 'categories.id', '=', 'expenses.category_id'
        )->join(
            'sources', 'sources.id', '=', 'expenses.source_id'
        )->select(
            DB::raw('sum(installments.cost) as total_cost, categories.id as category_id, YEAR(installments.paid_at) as year, MONTH(installments.paid_at) as month')
        )->where(
            'expenses.user_id', Auth::user()->id
        )->groupByRaw(
            'categories.id, installments.paid_at, EXTRACT(YEAR_MONTH FROM installments.paid_at)'
        )->havingBetween(
            'paid_at', [$year.'-01-01', $year.'-12-31']
        )->get()->map(function ($installment) {
            return [
                'category_id' => $installment->category_id,
                'cost_by_month' => $installment->total_cost,
                'year' => $installment->year,
                'month' => $installment->month
            ];
        });

        $revenues = Revenue::select(
            DB::raw('sum(income) as income, YEAR(date) as year, MONTH(date) as month')
        )->where(
            'user_id', Auth::user()->id
        )->groupByRaw(
            'date, EXTRACT(YEAR_MONTH FROM date)'
        )->havingBetween(
            'date', [$year.'-01-01', $year.'-12-31']
        )->get()->map(function ($revenue) {
            return [
                'income' => $revenue->income,
                'year' => $revenue->year,
                'month' => $revenue->month
            ];
        });

        return Inertia::render('Dashboard', [
            'installments' => $installments,
            'revenues' => $revenues,
            'categories' => $categories,
        ]);
    }
}
