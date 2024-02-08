<?php

namespace App\Models\manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Right extends Model
{
    use HasFactory;

    public function profiles(): belongsToMany
    {
        return $this->belongsToMany(Profile::class, 'profile_rights', 'rights_id', 'profile_id');
    }
}

