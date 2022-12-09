<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $dates = ['paid_at', 'formatted_paid_at'];
    protected $appends = ['formatted_paid_at', 'formatted_cost', 'cost_in_dollars'];

    public function formattedPaidAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->paid_at->format('M/Y'),
        );
    }

    public function formattedCost(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->cost / 100, 2, ',', '.'),
        );
    }

    public function costInDollars(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->cost / 100, 2, '.', ''),
        );
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

}
