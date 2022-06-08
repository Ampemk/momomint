<?php

namespace App\Webhooks;

use \Spatie\WebhookClient\WebhookProfile\WebhookProfile;
use Illuminate\Http\Request;


class ProcessWebhooksProfile implements WebhookProfile
{

    public function shouldProcess(Request $request): bool
    {
        switch ($request->object) {
            case 'whatsapp_business_account':
                return true;
            default:
                return false;
        }
    }
}
