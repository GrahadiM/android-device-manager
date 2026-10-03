<?php

namespace App\Console\Commands\AndroidManagement;

use App\Models\AndroidManagementEnterprise;
use App\Services\AndroidManagement\DeviceSyncService;
use Illuminate\Console\Command;

class SyncDevices extends Command
{
    protected $signature = 'android:sync-devices';

    protected $description = 'Synchronize Android Management devices into the local database';

    public function __construct(
        private DeviceSyncService $deviceSyncService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $enterprise = AndroidManagementEnterprise::query()
            ->where('status', 'active')
            ->whereNotNull('name')
            ->first();

        if (! $enterprise) {
            $this->error(
                'Enterprise aktif tidak ditemukan.'
            );

            return self::FAILURE;
        }

        $this->info(
            "Enterprise: {$enterprise->name}"
        );

        $count = $this->deviceSyncService->syncEnterprise(
            $enterprise
        );

        $this->info(
            "{$count} device berhasil disinkronkan."
        );

        return self::SUCCESS;
    }
}
