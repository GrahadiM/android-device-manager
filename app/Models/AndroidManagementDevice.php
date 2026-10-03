<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AndroidManagementDevice extends Model
{
    protected $table = 'android_management_devices';

    protected $fillable = [
        'enterprise_id',
        'asset_code',
        'google_device_name',
        'enrollment_token_name',
        'enrollment_token_expires_at',
        'enrollment_status',
        'manufacturer',
        'model',
        'android_version',
        'os_name',
        'build_number',
        'security_patch',
        'ram_gb',
        'management_mode',
        'status',
        'assigned_phone_number',
        'allowed_primary_app',
        'is_test_device',
        'notes',
    ];

    protected $casts = [
        'enrollment_token_expires_at' => 'datetime',
        'is_test_device' => 'boolean',
    ];

    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(
            AndroidManagementEnterprise::class,
            'enterprise_id'
        );
    }
}
