<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;

    protected $dates = ['date', 'formatted_date'];

    protected $appends = ['formatted_date', 'formatted_income'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formattedDate(): Attribute
    {
        return new Attribute(
            get: fn () => $this->date->format('d/m/Y'),
        );
    }

    public function formattedIncome(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->income / 100, 2, ',', '.'),
        );
    }
}
