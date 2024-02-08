<?php

namespace App\Models\manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pimLocale extends Model
{
    use HasFactory;

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
