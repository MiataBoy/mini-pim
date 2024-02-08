<?php

namespace App\Models\pim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation_type extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
}
