<?php

namespace App\Services\AndroidManagement;

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

    public function createLabEnrollmentToken(
        AndroidManagementEnterprise $enterprise
    ): EnrollmentToken {
        $service = $this->getClient();

        $policyName = $enterprise->name
            . '/policies/office-whatsapp';

        $token = new EnrollmentToken();

        $token->setPolicyName($policyName);

        // 24 jam dalam format protobuf Duration.
        $token->setDuration('86400s');

        $token->setOneTimeOnly(true);

        $token->setAdditionalData(
            'asset_code=VIVO-Y12-001'
        );

        return $service->enterprises_enrollmentTokens->create(
            $enterprise->name,
            $token
        );
    }
}
