<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\MomoStatement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;
use Illuminate\Support\Str;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessWebhookJob extends SpatieProcessWebhookJob
{

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payload = json_decode($this->webhookCall->payload);
        $value = $payload->entry[0]->changes[0]->value;
        $messages = $value->messages[0];

        if ($messages->type !== 'text') {
            return false;
        }

        $contact = $value->contacts[0];
        $message = $messages->text->body;
        $phone_number = $contact->wa_id;

        $account = Account::where('account_number', $phone_number)->first();

        if (Str::of($message)->startsWith('Payment made for')) {
            $this->startWithPayment($message, $phone_number, $account);
        }

        if (Str::of($message)->startsWith('Your payment of')) {
            $this->startWithYour($message, $phone_number, $account);
        }
    }

    public function startWithPayment($message, $phone_number, $account)
    {
        preg_match_all('/([0-9]+\.[0-9]+)/', $message, $matches);

        $getCurrentBalancePosition = strpos($message, 'Current');
        $newString = substr($message, 0, $getCurrentBalancePosition);
        $newStringArray = explode(' to ', $newString);

        preg_match_all('!\d+!', $newStringArray[1], $hasNumber);

        if (!empty($hasNumber[0][0])) {
            $toNumber = $hasNumber[0][0];

            $nameOrCompany = preg_replace('/[0-9]+/', '', $newStringArray[1]);
            $nameOrCompany = str_replace('()', '', $nameOrCompany);
        } else {
            $nameOrCompany = $newStringArray[1];
            $toNumber = 0;
        }

        $amount = $matches[0][0];
        $current_balance = $matches[0][1];
        $available_balance = $matches[0][2];
        $fees_charged = $matches[0][3];
        if (count($matches) === 4) {
            $tax_charged = $matches[0][4];
        } else {
            $tax_charged = 0.00;
        }
        $transaction_id = $this->getTransactionID($message);
        $reference = $this->getReference($message, 1);

        $momo_statement = new MomoStatement();
        $momo_statement->from_name = $account->user->first_name . ' ' . $account->user->last_name;
        $momo_statement->amount = $amount;
        $momo_statement->to_no = $toNumber;
        $momo_statement->to_name = $nameOrCompany;
        $momo_statement->ref = $reference;
        $momo_statement->fees = $fees_charged + $tax_charged;
        $momo_statement->f_id = $transaction_id;
        $momo_statement->bal_before = $current_balance;
        $momo_statement->bal_after = $available_balance;
        $momo_statement->from_no = $phone_number;
        $momo_statement->account_id = $account->id;
        $momo_statement->user_id = $account->user->id;
        $momo_statement->transaction_date =  date('Y-m-d H:i:s', strtotime($this->WebhookCall->timestamp));
        $momo_statement->save();
    }

    public function startWithYour($message, $phone_number, $account)
    {
        $message = 'Payment made for GHS 60.00 to GHBUBBLES   LTD (233555522327) Current Balance: GHS 216.12 . Available Balance: GHS 216.12. Reference: bubbles. Transaction ID: 17644660524. Fee charged: GHS0.60. Download the MoMo App for a Faster & Easier Experience   Click here:  http://mtnghana.app.link/nsBnhItDoob';
        preg_match_all('/([0-9]+\.[0-9]+)/', $message, $matches);

        $getCurrentBalancePosition = strpos($message, 'Current');
        $newString = substr($message, 0, $getCurrentBalancePosition);
        $newStringArray = explode(' to ', $newString);

        preg_match_all('!\d+!', $newStringArray[1], $hasNumber);

        if (!empty($hasNumber[0][0])) {
            $toNumber = $hasNumber[0][0];

            $nameOrCompany = preg_replace('/[0-9]+/', '', $newStringArray[1]);
            $nameOrCompany = str_replace('()', '', $nameOrCompany);
        } else {
            $nameOrCompany = $newStringArray[1];
            $toNumber = 0;
        }

        $amount = $matches[0][0];
        $current_balance = $matches[0][1];
        $available_balance = $matches[0][2];
        $fees_charged = $matches[0][3];
        if (count($matches) === 4) {
            $tax_charged = $matches[0][4];
        } else {
            $tax_charged = 0.00;
        }
        $reference = $this->getReference($message, 2);
        $transaction_id = $this->getTransactionID($message);

        $momo_statement = new MomoStatement();
        $momo_statement->from_name = $account->user->first_name . ' ' . $account->user->last_name;
        $momo_statement->amount = $amount;
        $momo_statement->to_no = $toNumber;
        $momo_statement->to_name = $nameOrCompany;
        $momo_statement->ref = $reference;
        $momo_statement->fees = $fees_charged + $tax_charged;
        $momo_statement->f_id = $transaction_id;
        $momo_statement->bal_before = $current_balance;
        $momo_statement->bal_after = $available_balance;
        $momo_statement->from_no = $phone_number;
        $momo_statement->account_id = $account->id;
        $momo_statement->user_id = $account->user->id;
        $momo_statement->transaction_date =  date('Y-m-d H:i:s', strtotime($this->WebhookCall->timestamp));
        $momo_statement->save();
    }

    //get the reference 
    public function getReference($text, $type)
    {

        switch ($type) {
            case 1:
                $endText = 'Transaction ID';
                break;
            case 2:
                $endText = 'Financial';
                break;
            default:
                $endText = 'Financial';
        }

        $getStartingPosition = strpos($text, 'Reference:');
        $getStartingPostionBetweenReference = strpos($text, $endText);
        $reference = substr($text, $getStartingPosition, $getStartingPostionBetweenReference - $getStartingPosition);
        $reference_string = str_replace('Reference:', '', $reference);
        return str_replace('.', '', $reference_string);
    }

    public function getTransactionID($text)
    {
        $transaction_id_position = strpos($text, 'Transaction Id') + 15;
        $transaction_id = substr($text, $transaction_id_position, 12);

        return $transaction_id;
    }
}
