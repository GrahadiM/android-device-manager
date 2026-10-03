<?php

namespace App\Services\AndroidManagement;

use App\Models\AndroidManagementDevice;
use App\Models\AndroidManagementEnterprise;
use Google\Service\AndroidManagement;
use Google\Service\AndroidManagement\EnrollmentToken;

class EnrollmentService
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
     * Membuat enrollment token untuk device tertentu.
     *
     * Asset code berasal dari database, bukan hardcode.
     */
    public function createEnrollmentToken(
        AndroidManagementEnterprise $enterprise,
        AndroidManagementDevice $device
    ): EnrollmentToken {
        if (! $device->asset_code) {
            throw new \RuntimeException(
                'Device tidak memiliki asset code.'
            );
        }

        if ($device->enterprise_id !== $enterprise->id) {
            throw new \RuntimeException(
                'Device tidak terhubung dengan enterprise yang dipilih.'
            );
        }

        if ($device->google_device_name) {
            throw new \RuntimeException(
                'Device sudah memiliki Google device name dan kemungkinan sudah enrolled.'
            );
        }

        $service = $this->getClient();

        $policyName = $enterprise->name
            . '/policies/office-whatsapp';

        $token = new EnrollmentToken();

        $token->setPolicyName(
            $policyName
        );

        // 24 jam dalam protobuf Duration.
        $token->setDuration(
            '86400s'
        );

        $token->setOneTimeOnly(
            true
        );

        /*
         * Metadata ini bukan secret token.
         *
         * Contoh:
         * {
         *     "asset_code": "LAB-ANDROID-001"
         * }
         */
        $token->setAllowPersonalUsage(
            'PERSONAL_USAGE_DISALLOWED_USERLESS'
        );

        $token->setAdditionalData(
            json_encode([
                'asset_code' => $device->asset_code,
            ], JSON_THROW_ON_ERROR)
        );

        return $service->enterprises_enrollmentTokens->create(
            $enterprise->name,
            $token
        );
    }
}
