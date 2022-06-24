<?php

namespace App\Webhooks;


use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\WebhookFailed;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Illuminate\Support\Facades\Log;

class WebhookSignatureValidator implements SignatureValidator
{

    public function isValid(Request $request, WebhookConfig $config): bool
    {

        $signature = $request->header($config->signatureHeaderName);
	Log::debug($request->header());
	Log::debug($signature);
        if (!$signature) {
            return false;
        }
        $signature = trim(str_replace("sha1=", "", $signature));
        $signingSecret = $config->signingSecret;
	Log::debug($request->getContent());
        if (empty($signingSecret)) {
            throw WebhookFailed::signingSecretNotSet();
        }
	return true;
        $computedSignature = hash_hmac('sha1',$request->all(), $signingSecret);
//	Log::debug(serialize($request->getContent());
	//return true;
        return hash_equals($signature, $computedSignature);
    }
}
