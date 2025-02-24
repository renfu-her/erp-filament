<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'level',
        'total_purchase',
        'status',
    ];

    protected $casts = [
        'total_purchase' => 'decimal:2',
    ];
} 