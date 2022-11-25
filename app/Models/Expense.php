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

    // public function toArray()
    // {
    //     return [
    //         'date' => $this->date->format('Y-m-d'),
    //         'formatted_date' => $this->formatted_date,
    //         'cost' => $this->cost,
    //         'description' => $this->description,
    //         'observation' => $this->observation,
    //     ];
    // }
}
