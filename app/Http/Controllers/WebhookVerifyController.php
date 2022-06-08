<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookVerifyController extends Controller
{
    //

    public function verifyEndpoint(Request $request)
    {
        if ($request->query('hub_verify_token') === 'fa6640f0a8d45d38c524f33af1721d0d') {
		return $request->query('hub_challenge');
        }
    }
}
