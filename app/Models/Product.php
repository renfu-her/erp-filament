<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', // spa, product, treatment, experience
        'description',
        'price',
        'stock',
        'safety_stock',
        'discount',
        'status',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ];
} 