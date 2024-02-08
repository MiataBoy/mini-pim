<?php

namespace App\Models\pim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class specifications_products_values extends Model
{
    use HasFactory;

    public function products() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function specifications() {
        return $this->belongsTo(Specification::class, 'specification_id', 'id');
    }

    public function translations() {
        return ($this->hasMany(specifications_products_values_translations::class, 'value_id', 'id'));
    }

    public function getValueAttribute($value) {
        if ($value === '1') {
            return true;
        }

        if ($value === '0') {
            return false;
        }

        return $value;
    }
}
