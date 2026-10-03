<?php

namespace App\Console\Commands\AndroidManagement;

use App\Services\AndroidManagement\EnterpriseService;
use Illuminate\Console\Command;

class CreateEnterpriseSignupUrl extends Command
{
    protected $signature = 'android:enterprise-signup';

    protected $description = 'Create an Android Management enterprise signup URL';

    public function handle(
        EnterpriseService $enterpriseService
    ): int {
        $callbackUrl = rtrim(
            config('app.url'),
            '/'
        ) . config(
            'google.android_management.callback_path'
        );

        $enterprise = $enterpriseService->createSignupUrl(
            $callbackUrl
        );

        $this->newLine();

        $this->info('Enterprise signup URL berhasil dibuat.');

        $this->line(
            'Signup URL Name: ' .
            $enterprise->signup_url_name
        );

        $this->line(
            'Callback State: ' .
            $enterprise->callback_state
        );

        $this->newLine();

        $this->warn('Signup URL:');

        $this->line(
            $enterprise->signup_url
        );

        $this->newLine();

        $this->warn(
            'Jangan gunakan URL ini jika callback belum dapat diakses dari internet.'
        );

        return self::SUCCESS;
    }
}
