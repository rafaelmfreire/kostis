<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $dates = ['bought_at', 'paid_at', 'formatted_bought_at', 'formatted_paid_at'];

    protected $appends = ['formatted_bought_at', 'formatted_paid_at', 'formatted_cost'];

    public function formattedBoughtAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->bought_at->format('d/m/Y'),
        );
    }

    public function formattedPaidAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->paid_at->format('d/m/Y'),
        );
    }

    public function formattedCost(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->cost / 100, 2, ',', '.'),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
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
            'paid_at' => $this->paid_at->toISOString(),
            'formatted_paid_at' => $this->formatted_paid_at,
            'cost' => $this->cost,
            'formatted_cost' => $this->formatted_cost,
            'description' => $this->description,
            'observation' => $this->observation,
            'category_name' => $this->category->name,
            'category_color' => $this->category->color,
            'source_name' => $this->source->name,
            'source_color' => $this->source->color,
        ];
    }
}
