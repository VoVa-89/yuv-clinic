<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public function isConfigured(): bool
    {
        $secret = config('clinic.recaptcha.secret_key');

        return is_string($secret) && $secret !== '';
    }

    public function verify(?string $responseToken, ?string $remoteIp = null): bool
    {
        if (! $this->isConfigured()) {
            return true;
        }

        if (empty($responseToken)) {
            return false;
        }

        $res = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('clinic.recaptcha.secret_key'),
            'response' => $responseToken,
            'remoteip' => $remoteIp,
        ]);

        if (! $res->successful()) {
            return false;
        }

        $data = $res->json();

        return (bool) ($data['success'] ?? false);
    }
}
