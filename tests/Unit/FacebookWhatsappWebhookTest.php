<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FacebookWhatsappWebhookTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $payload = json_decode('{"entry": [{"id": "WHATSAPP_BUSINESS_ACCOUNT_ID", "changes": [{"field": "messages", "value": {"contacts": [{"wa_id": "16315551234", "profile": {"name": "Kerry Fisher"}}], "messages": [{"id": "ABGGFlA5FpafAgo6tHcNmNjXmuSf", "from": "16315551234", "text": {"body": "Hello this is an answer"}, "type": "text", "timestamp": "1518694235"}], "metadata": {"phone_number_id": "17206422373", "display_phone_number": "7206422373"}, "messaging_product": "whatsapp"}}]}], "object": "whatsapp_business_account"}');

        $value = $payload->entry[0]->changes[0]->value;
        $contact = $value->contacts[0];
        $messages = $value->messages;

        $name = $contact->profile->name;
        $number = $contact->wa_id;

        foreach ($messages as $message) {

            echo $message->id;
            // dd($message);
        }


        $this->assertTrue(true);
    }
}
