<?php

namespace App\Console\Commands\AndroidManagement;

use App\Models\AndroidManagementEnterprise;
use App\Services\AndroidManagement\PolicyService;
use Illuminate\Console\Command;

class CreateOfficeWhatsappPolicy extends Command
{
    protected $signature = 'android:policy-office-whatsapp';

    protected $description = 'Create or update the office WhatsApp Android Management policy';

    public function handle(
        PolicyService $policyService
    ): int {
        $enterprise = AndroidManagementEnterprise::query()
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if (! $enterprise) {
            $this->error(
                'Tidak ada Android Management Enterprise yang aktif.'
            );

            return self::FAILURE;
        }

        $this->info(
            'Enterprise: ' . $enterprise->name
        );

        $policy = $policyService->createOfficeWhatsappPolicy(
            $enterprise
        );

        $this->newLine();

        $this->info('Policy berhasil dibuat / diperbarui.');

        $this->line(
            'Policy name: ' . $policy->getName()
        );

        $this->line(
            'Policy version: ' . $policy->getVersion()
        );

        return self::SUCCESS;
    }
}
