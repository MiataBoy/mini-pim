<?php

namespace App\Models\pim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_relation extends Model
{
    use HasFactory;

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->belongsTo(Product::class, 'child_id', 'id');
    }
}
