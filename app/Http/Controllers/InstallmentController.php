<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Installment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InstallmentController extends Controller
{
    public function edit(Expense $expense, Installment $installment)
    {
        $installment = Auth::user()->expenses()->findOrFail($expense->id)->installments()->findOrFail($installment->id);

        return Inertia::render('Installments/Edit', ['expense' => $expense, 'installment' => $installment]);
    }

    public function update(Expense $expense, Installment $installment)
    {
        $installment = Auth::user()->expenses()->findOrFail($expense->id)->installments()->findOrFail($installment->id);
        
        $this->validate(request(), [
            'number' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'paid_at' => ['required', 'date_format:Y-m'],
        ]);

        $installment->update([
            'number' => request('number'),
            'paid_at' => Carbon::parse(request('paid_at')),
            'cost' => request('cost') * 100,
        ]);

        return redirect()->route('expenses.edit', ['expense' => $expense]);
    }
}
