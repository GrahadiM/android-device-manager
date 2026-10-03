<?php

namespace App\Console\Commands\AndroidManagement;

use App\Models\AndroidManagementDevice;
use App\Models\AndroidManagementEnterprise;
use App\Services\AndroidManagement\EnrollmentService;
use Illuminate\Console\Command;

class CreateEnrollmentToken extends Command
{
    protected $signature = 'android:enrollment-token
                            {assetCode : Asset code device yang akan dienroll}';

    protected $description = 'Create a one-time enrollment token for a specific Android device';

    public function __construct(
        private EnrollmentService $enrollmentService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $assetCode = $this->argument('assetCode');

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

        $device = AndroidManagementDevice::query()
            ->where('asset_code', $assetCode)
            ->first();

        if (! $device) {
            $this->error(
                "Device dengan asset code [{$assetCode}] tidak ditemukan."
            );

            return self::FAILURE;
        }

        if ($device->google_device_name) {
            $this->error(
                'Device ini sudah memiliki Google device name.'
            );

            $this->line(
                "Google device: {$device->google_device_name}"
            );

            return self::FAILURE;
        }

        if (
            $device->enrollment_token_expires_at
            && $device->enrollment_token_expires_at->isFuture()
        ) {
            $this->error(
                'Device masih memiliki enrollment token yang belum expired.'
            );

            $this->line(
                'Expiration: '
                . $device->enrollment_token_expires_at->toISOString()
            );

            $this->warn(
                'Gunakan token yang sudah ada atau tunggu sampai expired.'
            );

            return self::FAILURE;
        }

        /*
         * Device harus menggunakan enterprise aktif.
         */
        if (
            $device->enterprise_id !== null
            && $device->enterprise_id !== $enterprise->id
        ) {
            $this->error(
                'Device terhubung ke enterprise yang berbeda.'
            );

            return self::FAILURE;
        }

        /*
         * Pastikan device terhubung ke enterprise aktif.
         */
        if ($device->enterprise_id === null) {
            $device->update([
                'enterprise_id' => $enterprise->id,
            ]);

            $device->refresh();
        }

        $this->info(
            "Enterprise: {$enterprise->name}"
        );

        $this->info(
            "Asset code: {$device->asset_code}"
        );

        $this->info(
            "Policy: {$enterprise->name}/policies/office-whatsapp"
        );

        try {
            $token = $this->enrollmentService
                ->createEnrollmentToken(
                    enterprise: $enterprise,
                    device: $device
                );
        } catch (\Throwable $exception) {
            $this->error(
                'Gagal membuat enrollment token.'
            );

            $this->line(
                $exception->getMessage()
            );

            return self::FAILURE;
        }

        $expiration = $token->getExpirationTimestamp();

        $device->update([
            'enrollment_token_name' => $token->getName(),
            'enrollment_token_expires_at' => $expiration,
            'enrollment_status' => 'token_created',
        ]);

        $this->newLine();

        $this->info(
            'Enrollment token berhasil dibuat.'
        );

        $this->line(
            'Token name: ' . $token->getName()
        );

        $this->line(
            'Expiration: ' . $expiration
        );

        $this->line(
            'One time only: '
            . ($token->getOneTimeOnly() ? 'yes' : 'no')
        );

        $this->newLine();

        $this->warn(
            'Token value dan QR payload TIDAK ditampilkan oleh command.'
        );

        $this->warn(
            'Gunakan metode provisioning yang aman untuk device.'
        );

        return self::SUCCESS;
    }
}
