<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model {
  use HasFactory, Searchable;

  protected $fillable = [
    'name', 'price',
    'description', 'amount',
    'available', 'min_amount_purchase'
  ];

  protected $casts = [
    'available' => 'boolean',
    'price' => 'float',
    'amount' => 'integer',
    'min_amount_purchase' => 'integer'
  ];
}
