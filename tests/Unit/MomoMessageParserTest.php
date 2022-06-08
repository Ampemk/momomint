<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MomoMessageParserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_message_parser()
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
        $reference = $this->getReference($message, 1);

        echo 'Amount: ' . $amount;
        echo 'Number:' . $toNumber;
        echo 'Name: ' . $nameOrCompany;
        echo 'current balance: ' . $current_balance;
        echo 'available balance: ' . $available_balance;
        echo 'fees charged: ' . $fees_charged;
        echo 'tax charged: ' . $tax_charged;
        echo 'Reference: ' . $reference;

        //transaction_id
        echo 'transaction id: ' . $this->getTransactionID($message);
    }



    public function test_message_parser_other()
    {
        $message = 'Your payment of GHS 35.00 to INTEROPERABILITY PUSH  has been completed at 2022-06-05 11:10:09. Your new balance: GHS 27.37. Fee was GHS 0.38 Tax was GHS -. Reference: senderMsg. Financial Transaction Id: 17663056877. External Transaction Id: 17663056877.Download the MoMo App for a Faster & Easier Experience   Click here:  http://mtnghana.app.link/nsBnhItDoob';
        preg_match_all('/([0-9]+\.[0-9]+)/', $message, $matches);

        $getCurrentBalancePosition = strpos($message, 'has been');
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
        $fees_charged = $matches[0][2];
        if (count($matches) === 3) {
            $tax_charged = $matches[0][3];
        } else {
            $tax_charged = 0.00;
        }
        $reference = $this->getReference($message, 2);



        echo 'Amount: ' . $amount;
        echo 'Number:' . $toNumber;
        echo 'Name: ' . $nameOrCompany;
        echo 'current balance: ' . $current_balance;
        echo 'fees charged: ' . $fees_charged;
        echo 'tax charged: ' . $tax_charged;
        echo 'Reference: ' . $reference;

        //transaction_id
        echo 'transaction id: ' . $this->getTransactionID($message);
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
