<?php

namespace App\Services\AndroidManagement;

use Google\Client;
use Google\Service\AndroidManagement;

class AndroidManagementClient
{
    public function make(): AndroidManagement
    {
        $client = new Client();

        $client->setApplicationName(
            config('app.name')
        );

        $credentials = base_path(
            config('google.credentials')
        );

        if (! file_exists($credentials)) {
            throw new \RuntimeException(
                "Google service account credential tidak ditemukan: {$credentials}"
            );
        }

        $client->setAuthConfig($credentials);

        $client->setScopes([
            config('google.android_management.scope'),
        ]);

        return new AndroidManagement($client);
    }
}
