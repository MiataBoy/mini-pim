<?php

namespace App\Models\pim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specifications_translation extends Model
{
    use HasFactory;

    protected $fillable = ['specification_id', 'name', 'locale', 'description'];

    public function specification() {
        $this->belongsTo(Specification::class);
    }
}
