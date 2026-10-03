<?php

namespace App\Services\AndroidManagement;

use App\Models\AndroidManagementEnterprise;
use Google\Service\AndroidManagement;
use Google\Service\AndroidManagement\Enterprise;
use Illuminate\Support\Str;

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
        string $callbackBaseUrl
    ): AndroidManagementEnterprise {
        $service = $this->getClient();

        /*
         * State lokal untuk menghubungkan callback
         * dengan record signup di database.
         */
        $callbackState = (string) Str::uuid();

        $callbackUrl = $callbackBaseUrl
            . '?state=' . urlencode($callbackState);

        /*
         * Google Android Management API:
         *
         * signupUrls.create({
         *     projectId,
         *     callbackUrl
         * })
         */
        $signupUrl = $service->signupUrls->create([
            'projectId' => config('google.project_id'),
            'callbackUrl' => $callbackUrl,
        ]);

        return AndroidManagementEnterprise::create([
            'signup_url_name' => $signupUrl->getName(),
            'callback_state' => $callbackState,
            'signup_url' => $signupUrl->getUrl(),
            'status' => 'pending_signup',
        ]);
    }

    public function createEnterprise(
        string $enterpriseToken,
        string $callbackState
    ): AndroidManagementEnterprise {
        $service = $this->getClient();

        $record = AndroidManagementEnterprise::query()
            ->where('callback_state', $callbackState)
            ->firstOrFail();

        if ($record->status === 'active' && $record->name) {
            return $record;
        }

        $enterprise = new Enterprise();

        $result = $service->enterprises->create(
            $enterprise,
            [
                'projectId' => config('google.project_id'),
                'signupUrlName' => $record->signup_url_name,
                'enterpriseToken' => $enterpriseToken,
            ]
        );

        $record->update([
            'name' => $result->getName(),
            'display_name' => $result->getEnterpriseDisplayName(),
            'status' => 'active',
        ]);

        return $record->fresh();
    }
}
