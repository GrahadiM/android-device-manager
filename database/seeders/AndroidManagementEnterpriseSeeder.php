<?php

namespace Database\Seeders;

use App\Models\AndroidManagementEnterprise;
use Illuminate\Database\Seeder;

class AndroidManagementEnterpriseSeeder extends Seeder
{
    public function run(): void
    {
        AndroidManagementEnterprise::updateOrCreate(
            [
                'name' => 'enterprises/LC01ug5lcl',
            ],
            [
                'display_name' => null,

                'signup_url_name' => null,

                'callback_state' => null,

                'signup_url' => null,

                'status' => 'active',
            ]
        );
    }
}
