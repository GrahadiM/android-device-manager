<?php

namespace App\Services\AndroidManagement;

use App\Models\AndroidManagementEnterprise;
use Google\Service\AndroidManagement;
use Google\Service\AndroidManagement\ApplicationPolicy;
use Google\Service\AndroidManagement\Policy;

class PolicyService
{
    public function __construct(
        private AndroidManagementClient $client
    ) {
    }

    public function getClient(): AndroidManagement
    {
        return $this->client->make();
    }

    public function createOfficeWhatsappPolicy(
        AndroidManagementEnterprise $enterprise
    ): Policy {
        $service = $this->getClient();

        $policyName = $enterprise->name . '/policies/office-whatsapp';

        $policy = new Policy();

        $policy->setName($policyName);

        $policy->setApplications([
            $this->makeWhatsappApplicationPolicy(),
        ]);

        $policy->setInstallAppsDisabled(true);
        $policy->setUninstallAppsDisabled(true);
        $policy->setFactoryResetDisabled(true);
        $policy->setModifyAccountsDisabled(true);
        $policy->setAddUserDisabled(true);

        $policy->setPlayStoreMode('WHITELIST');

        return $service->enterprises_policies->patch(
            $policyName,
            $policy
        );
    }

    private function makeWhatsappApplicationPolicy(): ApplicationPolicy
    {
        $application = new ApplicationPolicy();

        $application->setPackageName('com.whatsapp');

        $application->setInstallType('FORCE_INSTALLED');

        return $application;
    }
}
