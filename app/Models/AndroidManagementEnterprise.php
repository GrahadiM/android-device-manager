<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AndroidManagementEnterprise extends Model
{
    protected $table = 'android_management_enterprises';

    protected $fillable = [
        'name',
        'display_name',
        'signup_url_name',
        'callback_state',
        'signup_url',
        'status',
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(
            AndroidManagementDevice::class,
            'enterprise_id'
        );
    }
}
