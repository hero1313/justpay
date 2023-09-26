<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function form()
    {
        return view('components.stripe.form');
    }
    public function makePayment(Request $request)
    {
      $input = $request->all();
      \Stripe\Stripe::setApiKey('sk_test_51LTMwVFPefbRqaCaagGoWRCDMcQtH63d9tYttN51KvFIuDDziwZiJGl76QdssCbEeXGP0udyHl8QXaLDLsHY6OTQ005jLgcScC');
      $charge = \Stripe\Charge::create([
        'source' => $_POST['stripeToken'],
        'description' => "10 cucumbers from Roger's Farm",
        'amount' => 2000,
        'currency' => 'usd',
      ]);
      if($charge->status == 'succeeded'){
        return redirect('/tbc_callback');
      }
    }
}
