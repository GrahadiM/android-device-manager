<?php

namespace Database\Seeders;

use App\Models\AndroidManagementDevice;
use Illuminate\Database\Seeder;

class AndroidManagementDeviceSeeder extends Seeder
{
    public function run(): void
    {
        AndroidManagementDevice::updateOrCreate(
            [
                'asset_code' => 'LAB-ANDROID-001',
            ],
            [
                'enterprise_id' => null,

                'google_device_name' => null,

                'enrollment_token_name' => null,

                'enrollment_token_expires_at' => null,

                'enrollment_status' => 'pending',

                'manufacturer' => 'Google',

                'model' => 'CorporateLab Emulator',

                'android_version' => '11',

                'os_name' => 'Android',

                'build_number' => null,

                'security_patch' => null,

                'ram_gb' => null,

                'management_mode' => null,

                'status' => 'pending_enrollment',

                'assigned_phone_number' => null,

                'allowed_primary_app' => 'com.whatsapp',

                'is_test_device' => true,

                'notes' => 'Android Emulator lab device for Android Management API testing.',
            ]
        );
    }
}
