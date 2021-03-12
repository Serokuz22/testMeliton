<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Convert extends Model
{
    protected $fillable = [
        'currency_from',
        'currency_to',
        'value',
        'converted_value',
        'rate'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
