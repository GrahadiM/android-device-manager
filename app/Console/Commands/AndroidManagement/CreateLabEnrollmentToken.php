<?php

namespace App\Console\Commands\AndroidManagement;

use App\Models\AndroidManagementEnterprise;
use App\Services\AndroidManagement\EnrollmentService;
use Illuminate\Console\Command;

class CreateLabEnrollmentToken extends Command
{
    protected $signature = 'android:enrollment-token';

    protected $description = 'Create a one-time enrollment token for the lab device';

    public function handle(
        EnrollmentService $enrollmentService
    ): int {
        $enterprise = AndroidManagementEnterprise::query()
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if (! $enterprise) {
            $this->error(
                'Tidak ada Android Management Enterprise yang aktif.'
            );

            return self::FAILURE;
        }

        $this->info(
            'Enterprise: ' . $enterprise->name
        );

        $token = $enrollmentService->createLabEnrollmentToken(
            $enterprise
        );

        $this->newLine();

        $this->info(
            'Enrollment token berhasil dibuat.'
        );

        $this->line(
            'Token name: ' . $token->getName()
        );

        $this->line(
            'Policy: ' . $token->getPolicyName()
        );

        $this->line(
            'Expiration: ' . $token->getExpirationTimestamp()
        );

        $this->line(
            'One time only: ' .
            ($token->getOneTimeOnly() ? 'yes' : 'no')
        );

        $this->newLine();

        // Menampilkan token value dan QR code payload dapat menimbulkan risiko keamanan.
        // $this->warn('Enrollment token value:');
        // $this->line(
        //     $token->getValue()
        // );

        // $this->newLine();

        // Menampilkan QR code payload dapat menimbulkan risiko keamanan.
        // $this->warn('QR code payload:');
        // $this->line(
        //     $token->getQrCode()
        // );

        $this->info('Enrollment token berhasil dibuat.');

        $this->line(
            'Token name: ' . $token->getName()
        );

        $this->line(
            'Policy: ' . $token->getPolicyName()
        );

        $this->line(
            'Expiration: ' . $token->getExpirationTimestamp()
        );

        $this->line(
            'One time only: '
            . ($token->getOneTimeOnly() ? 'yes' : 'no')
        );

        $this->warn(
            'Token value dan QR payload tidak ditampilkan.'
        );

        return self::SUCCESS;
    }
}
