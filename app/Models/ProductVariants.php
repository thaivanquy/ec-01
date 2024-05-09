<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariants extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'title',
        'sku',
        'barcode',
        'price',
        'image',
        'publish',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
 
    public function AttributeValues()
    {
        return $this->belongsToMany(AttributeValues::class);
    }
}
