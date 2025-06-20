<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'number',
        'brand',
        'bank',
        'available_limit',
        'user_dni',
        'user_first_name',
        'user_last_name',
    ];
}
