<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $dates = ['date', 'formatted_date'];

    protected $appends = ['formatted_date', 'formatted_cost'];

    public function formattedDate(): Attribute
    {
        return new Attribute(
            get: fn () => $this->date->format('d/m/Y'),
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
            'date' => $this->date->toISOString(),
            'formatted_date' => $this->formatted_date,
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
