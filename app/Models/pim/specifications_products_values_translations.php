<?php

namespace App\Models\pim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class specifications_products_values_translations extends Model
{
    use HasFactory;

    public function values() {
        return $this->belongsTo(specifications_products_values::class, 'value_id', 'id');
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
