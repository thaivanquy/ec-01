<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'regular_price',
        'compare_price',
        'category_id',
        'sub_category_id',
        'brand_id',
        'is_featured',
        'track_qty',
        'status',
    ];

    protected $casts = [
        'publish' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function ProductVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function setRegularPriceAttribute($value)
    {
        $this->attributes['regular_price'] = str_replace('.', '', $value);
    }

    public function setComparePriceAttribute($value)
    {
        $this->attributes['compare_price'] = str_replace('.', '', $value);
    }

    public function setBarcodeAttribute($value)
    {
        $this->attributes['barcode'] = str_replace(' ', '', $value);
    }
}
