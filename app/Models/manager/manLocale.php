<?php

namespace App\Models\manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class manLocale extends Model
{
    use HasFactory;

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getEnabledAttribute($enabled) {
        if ($enabled === '1') {
            return true;
        }

        if ($enabled === '0') {
            return false;
        }

        return $enabled;
    }
}
