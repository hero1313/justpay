<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayriffService;


class PayriffController extends Controller
{
    public function convert(Request $request, PayriffService $convert)
    {

    $paymentGateway = new PayriffService;

    $paymentPageUrl = $paymentGateway->createOrder(
    '100',             // amount
    'Asif Quliyev',    // description
    'AZN',             // currency
    'AZ',              // language
    );
    dd($convert);

    return redirect($paymentPageUrl);
        }
}