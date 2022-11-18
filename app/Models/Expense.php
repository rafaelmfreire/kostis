<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $dates = ['date'];

    protected $appends = ['date_formatted', 'cost_formatted'];

    public function formattedDate(): Attribute
    {
        return new Attribute(
            get: fn () => $this->date->format('d/m/Y'),
        );
    }

    // public function costFormatted(): Attribute
    // {
    //     return new Attribute(
    //         get: fn () => number_format($this->cost / 100, 2, ',', '.'),
    //     );
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toArray()
    {
        return [
            'date' => $this->date->format('Y-m-d'),
            'cost' => $this->cost,
            'description' => $this->description,
            'observation' => $this->observation,
        ];
    }
}
