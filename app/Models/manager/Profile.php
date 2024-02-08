<?php

namespace App\Models\manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Shows the hasMany connection between profile and user (profile may have many users)
     * @return HasMany
     */
    public function users(): hasMany
    {
        return $this->hasMany(User::class);
    }

    public function rights(): belongsToMany
    {
        return $this->belongsToMany(Right::class, 'profile_rights', 'profile_id', 'rights_id')->withPivot('right_assigned');
    }
}
