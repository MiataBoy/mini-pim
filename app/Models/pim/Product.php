<?php

namespace App\Models\pim;

use App\Models\productSpecification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public function assets()
    {
        return $this->hasMany(Products_asset::class, 'product_id', 'id');
    }

    public function specifications() {
        return $this->hasMany(productSpecification::class, 'product_id', 'id');
    }
}
