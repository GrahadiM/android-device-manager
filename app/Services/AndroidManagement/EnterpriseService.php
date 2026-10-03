<?php

namespace App\Services\AndroidManagement;

use Google\Service\AndroidManagement;

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
}
