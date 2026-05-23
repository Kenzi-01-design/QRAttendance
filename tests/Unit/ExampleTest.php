<?php

namespace Tests\Unit;

use App\Services\QrSignatureService;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_qr_signature_is_deterministic_and_verifiable(): void
    {
        config()->set('app.qr_secret', 'test-secret');

        $service = new QrSignatureService();
        $sig = $service->sign('2024-0001');

        $this->assertTrue($service->verify('2024-0001', $sig));
        $this->assertFalse($service->verify('2024-0002', $sig));
        $this->assertStringContainsString('sn=2024-0001', $service->payload('2024-0001'));
    }
}
