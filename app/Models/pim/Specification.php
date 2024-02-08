<?php

namespace App\Models\pim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    use HasFactory;

    protected $fillable = ['inputType_id'];

    public function translations() {
        return $this->hasMany(Specifications_translation::class);
    }

    public function inputType() {
        return $this->hasOne(Specification_inputType::class, 'id', 'inputType_id');
    }

    public function productValues() {
        return $this->hasMany(specifications_products_values::class, 'specification_id');
    }
}
