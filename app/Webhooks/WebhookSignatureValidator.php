<?php

namespace App\Webhooks;


use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\WebhookFailed;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;

class WebhookSignatureValidator implements SignatureValidator
{

    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $signature = $request->header($config->signatureHeaderName);
        if (!$signature) {
            return true;
        }
        $signature = trim(str_replace("sha1=", "", $signature));
        $signingSecret = $config->signingSecret;

        if (empty($signingSecret)) {
            throw WebhookFailed::signingSecretNotSet();
        }
        $computedSignature = hash_hmac('sha1', $request->getContent(), $signingSecret);
        return hash_equals($signature, $computedSignature);
    }
}
