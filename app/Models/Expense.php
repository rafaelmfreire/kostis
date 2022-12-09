<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $dates = ['bought_at', 'formatted_bought_at'];

    protected $appends = ['formatted_bought_at', 'formatted_cost', 'cost_in_dollars'];

    public function formattedBoughtAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->bought_at->format('d/m/Y'),
        );
    }

    // public function formattedPaidAt(): Attribute
    // {
    //     return new Attribute(
    //         get: fn () => $this->paid_at->format('d/m/Y'),
    //     );
    // }

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function addInstallments($startDate)
    {
        foreach (range(1, $this->installments_quantity) as $i) {
            $this->installments()->create([
                'expense_id' => $this->id,
                'cost' => $this->cost / $this->installments_quantity,
                'number' => $i,
                'paid_at' => Carbon::parse($startDate)->addMonth($i-1),
            ]);
        }
        return $this;
    }

    public function installmentQuantity()
    {
        return $this->installments()->count();
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'source_id' => $this->source_id,
            'bought_at' => $this->bought_at->toISOString(),
            'formatted_bought_at' => $this->formatted_bought_at,
            'installments_quantity' => $this->installments_quantity,
            'cost' => $this->cost,
            'formatted_cost' => $this->formatted_cost,
            'cost_in_dollars' => $this->cost_in_dollars,
            'description' => $this->description,
            'observation' => $this->observation,
            'category_name' => $this->category->name,
            'category_color' => $this->category->color,
            'source_name' => $this->source->name,
            'source_color' => $this->source->color,
        ];
    }
}
