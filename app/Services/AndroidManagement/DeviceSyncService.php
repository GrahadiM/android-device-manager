<?php

namespace App\Services\AndroidManagement;

use App\Models\AndroidManagementDevice;
use App\Models\AndroidManagementEnterprise;
use Google\Service\AndroidManagement\Device;

class DeviceSyncService
{
    public function __construct(
        private DeviceService $deviceService
    ) {
    }

    public function syncEnterprise(
        AndroidManagementEnterprise $enterprise
    ): int {
        $devices = $this->deviceService->listDevices(
            $enterprise
        );

        $count = 0;

        foreach ($devices as $device) {
            $this->syncDevice(
                enterprise: $enterprise,
                device: $device
            );

            $count++;
        }

        return $count;
    }

    public function syncDevice(
        AndroidManagementEnterprise $enterprise,
        Device $device
    ): AndroidManagementDevice {
        $deviceName = $device->getName();

        if (! $deviceName) {
            throw new \RuntimeException(
                'Google device tidak memiliki resource name.'
            );
        }

        $hardware = $device->getHardwareInfo();
        $software = $device->getSoftwareInfo();

        return AndroidManagementDevice::updateOrCreate(
            [
                'google_device_name' => $deviceName,
            ],
            [
                'enterprise_id' => $enterprise->id,

                'manufacturer' => $hardware?->getManufacturer(),

                'model' => $hardware?->getModel(),

                'android_version' => $software?->getAndroidVersion(),

                'build_number' => $software?->getAndroidBuildNumber(),

                'security_patch' => $software?->getSecurityPatchLevel(),

                'management_mode' => $device->getManagementMode(),

                'status' => $device->getState() ?? 'unknown',

                'is_test_device' => true,
            ]
        );
    }
}
