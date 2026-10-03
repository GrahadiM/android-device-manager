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
                'asset_code' => 'VIVO-Y12-001',
            ],
            [
                'enterprise_id' => null,

                /*
                 * Belum enrolled ke Google.
                 */
                'google_device_name' => null,

                'manufacturer' => 'vivo',
                'model' => 'vivo Y12',

                'android_version' => '9',
                'os_name' => 'Funtouch OS 9',

                'build_number' => 'PD1901_A_1.21.38',

                'security_patch' => '2020-06-01',

                'ram_gb' => 8,

                'management_mode' => null,

                'status' => 'local_testing',

                'assigned_phone_number' => null,

                /*
                 * Belum kita enforce sekarang.
                 * Ini hanya metadata rencana policy.
                 */
                'allowed_primary_app' => 'com.whatsapp',

                'is_test_device' => true,

                'notes' => 'Lab test device - vivo Y12. Data lokal/dummy. Belum enrolled ke Android Management API.',
            ]
        );
    }
}
