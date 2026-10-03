<?php

namespace App\Services\AndroidManagement;

use App\Models\AndroidManagementEnterprise;
use Google\Service\AndroidManagement;
use Google\Service\AndroidManagement\Device;

class DeviceService
{
    public function __construct(
        private AndroidManagementClient $client
    ) {
    }

    public function getClient(): AndroidManagement
    {
        return $this->client->make();
    }

    /**
     * Mengambil semua device yang terdaftar pada enterprise.
     */
    public function listDevices(
        AndroidManagementEnterprise $enterprise
    ): array {
        $service = $this->getClient();

        $response = $service->enterprises_devices
            ->listEnterprisesDevices(
                $enterprise->name
            );

        $devices = $response->getDevices();

        return $devices ?? [];
    }

    /**
     * Mengambil satu device berdasarkan resource name.
     */
    public function getDevice(
        string $deviceName
    ): Device {
        $service = $this->getClient();

        return $service->enterprises_devices->get(
            $deviceName
        );
    }
}
