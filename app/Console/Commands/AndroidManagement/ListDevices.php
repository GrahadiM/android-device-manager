<?php

namespace App\Console\Commands\AndroidManagement;

use App\Models\AndroidManagementEnterprise;
use App\Services\AndroidManagement\DeviceService;
use Illuminate\Console\Command;

class ListDevices extends Command
{
    protected $signature = 'android:devices';

    protected $description = 'Menampilkan device yang terdaftar pada Android Management enterprise';

    public function __construct(
        private DeviceService $deviceService
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

        $devices = $this->deviceService->listDevices(
            $enterprise
        );

        if ($devices === []) {
            $this->info(
                'Belum ada device yang terdaftar.'
            );

            return self::SUCCESS;
        }

        $this->newLine();

        foreach ($devices as $device) {
            $this->line(
                "Device: {$device->getName()}"
            );

            $this->line(
                '  State: '
                . ($device->getState() ?? '-')
            );

            $this->line(
                '  Management mode: '
                . ($device->getManagementMode() ?? '-')
            );

            $this->line(
                '  Ownership: '
                . ($device->getOwnership() ?? '-')
            );

            $this->line(
                '  Policy: '
                . ($device->getPolicyName() ?? '-')
            );

            $this->line(
                '  Policy compliant: '
                . ($device->getPolicyCompliant() ? 'yes' : 'no')
            );

            $this->newLine();
        }

        return self::SUCCESS;
    }
}
