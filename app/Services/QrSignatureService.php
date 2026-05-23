<?php

namespace App\Services;

class QrSignatureService
{
    public function sign(string $studentNo): string
    {
        $secret = (string) config('app.qr_secret');
        $raw = hash_hmac('sha256', $studentNo, $secret, true);

        return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
    }

    public function verify(string $studentNo, string $signature): bool
    {
        return hash_equals($this->sign($studentNo), $signature);
    }

    public function payload(string $studentNo): string
    {
        return http_build_query([
            'sn' => $studentNo,
            'sig' => $this->sign($studentNo),
        ]);
    }
}
