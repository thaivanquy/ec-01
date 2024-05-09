<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValues extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'attribute_id',
        'value',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attributes::class);
    }
}
