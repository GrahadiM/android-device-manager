<?php

namespace App\Services\AndroidManagement;

use App\Models\AndroidManagementEnterprise;
use Google\Service\AndroidManagement;
use Google\Service\AndroidManagement\Enterprise;

class EnterpriseService
{
    public function __construct(
        private AndroidManagementClient $client
    ) {
    }

    public function getClient(): AndroidManagement
    {
        return $this->client->make();
    }

    public function createSignupUrl(
        string $callbackUrl
    ): AndroidManagementEnterprise {
        $service = $this->getClient();

        $signupUrl = $service->signupUrls->create([
            'projectId' => config('google.project_id'),
            'callbackUrl' => $callbackUrl,
        ]);

        return AndroidManagementEnterprise::create([
            'signup_url_name' => $signupUrl->getName(),
            'signup_url' => $signupUrl->getUrl(),
            'status' => 'pending_signup',
        ]);
    }

    public function createEnterprise(
        string $enterpriseToken,
        string $signupUrlName
    ): AndroidManagementEnterprise {
        $service = $this->getClient();

        $enterprise = new Enterprise();

        $result = $service->enterprises->create(
            $enterprise,
            [
                'projectId' => config('google.project_id'),
                'signupUrlName' => $signupUrlName,
                'enterpriseToken' => $enterpriseToken,
            ]
        );

        $record = AndroidManagementEnterprise::query()
            ->where('signup_url_name', $signupUrlName)
            ->where('status', 'pending_signup')
            ->firstOrFail();

        $record->update([
            'name' => $result->getName(),
            'display_name' => $result->getDisplayName(),
            'status' => 'active',
        ]);

        return $record->fresh();
    }
}
