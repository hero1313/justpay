<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Payments\TBCpayment;
use App\Payments\openBanking;
use App\Payments\PayzePayment;
use App\Payments\Payriffpayment;
use App\Payments\BOGpayment;
use App\Payments\PayzeSplit;
use Illuminate\Support\Facades\Auth;
use Exception;
use Throwable;

class PaymentsController extends Controller
{
    public function tbcPayment(Request $request, $frontId)
    {
        $order = Order::where('front_code', '=', $frontId)->first();
        $company = User::find($order->user_id);
        $api_config = (object) [
            'client_id' => $company->tbc_id,
            'client_key' => $company->tbc_key
        ];
        try {
            $payment = new TBCpayment($api_config);
            $transaction = $payment->createOrder((object) $order);
            if ($transaction === null) {
                throw new Exception("Error while trying to make tbcnew order");
            }
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'tbc payment error',
                'message' => 'when order is not redirect payment page'
            ]);
        }

        if ($transaction->currency == 'GEL') {
            $currency = 1;
        } elseif ($transaction->currency == 'USD') {
            $currency = 2;
        } elseif ($transaction->currency == 'EUR') {
            $currency = 3;
        }
        // create transaction
        $transactions = new Transaction();
        $transactions->user_id = $company->id;
        $transactions->pay_id = $transaction->payId;
        $transactions->order_id = $transaction->order_id;
        $transactions->valuta = $currency;
        $transactions->total = $transaction->total;
        $transactions->pay_method = 1;
        $transactions->transaction_status = 2;
        $transactions->full_name = $request->name;
        $transactions->number = $request->number;
        $transactions->lat = $request->lat;
        $transactions->lng = $request->lng;
        $transactions->address = $request->address;
        $transactions->email = $request->email;
        $transactions->id_number = $request->id_number;
        $transactions->special_code = $request->special_code;
        $transactions->save();

        $status = $payment->transactionStatus((object) $transaction);

        return redirect($transaction->redirect);
    }


    public function payriffPayment(Request $request, Payriffpayment $convert, $frontId)
    {
        $order = Order::where('front_code', '=', $frontId)->first();
        $company = User::find($order->user_id);
        $amount = $order->total + $order->shiping;
        $payriffGateway = new Payriffpayment;
        $merchantSecretKey = $company->payriff_key;
        $merchantUniqueNumber = $company->payriff_id;
        $paymentPageUrl = $payriffGateway->createOrder(
            $amount, // amount
            $order->front_code,
            // description
            'AZN',
            // currency
            'AZ',
            // language
            $merchantSecretKey,
            $merchantUniqueNumber,
        );

        // create transaction
        $transactions = new Transaction();
        $transactions->user_id = $company->id;
        $transactions->pay_id = $payriffGateway->orderId;
        $transactions->sessionId = $payriffGateway->sessionId;
        $transactions->order_id = $order->id;
        $transactions->valuta = 1;
        $transactions->total = $amount;
        $transactions->pay_method = $request->payment_index;
        $transactions->transaction_status = 2;
        $transactions->full_name = $request->name;
        $transactions->number = $request->number;
        $transactions->lat = $request->lat;
        $transactions->lng = $request->lng;
        $transactions->address = $request->address;
        $transactions->email = $request->email;
        $transactions->id_number = $request->id_number;
        $transactions->special_code = $request->special_code;
        $transactions->save();

        return redirect($paymentPageUrl);
    }


    public function payze(Request $request, $frontId)
    {

        $order = Order::where('front_code', '=', $frontId)->first();
        $company = User::where('id', $order->user_id)->first();

        if ($order->total < 1) {
            return redirect()->back()->withErrors(['msg' => 'amount is less than 1']);
        }
        $config = (object) [
            'apiKey' => $company->payze_id,
            'apiSecret' => $company->payze_key,
            'amount' => $order->total,
            'userId' => $order->user_id
        ];
        $payment = new PayzePayment($config);
        $success = $payment->pay($config);

        // create transaction
        $transactions = new Transaction();
        $transactions->user_id = $company->id;
        $transactions->pay_id = $success->payId;
        $transactions->order_id = $order->id;
        $transactions->valuta = 1;
        $transactions->total = $order->total;
        $transactions->pay_method = 3;
        $transactions->transaction_status = 2;
        $transactions->full_name = $request->name;
        $transactions->number = $request->number;
        $transactions->lat = $request->lat;
        $transactions->lng = $request->lng;
        $transactions->address = $request->address;
        $transactions->email = $request->email;
        $transactions->id_number = $request->id_number;
        $transactions->special_code = $request->special_code;
        $transactions->save();

        if (!$success) {
            abort(404);
            return;
        }
        return redirect($success->redirect);
    }

    public function payzeSplit(Request $request, $frontId)
    {

        $order = Order::where('front_code', '=', $frontId)->first();
        $company = User::where('id', $order->user_id)->first();

        if ($order->total < 1) {
            return redirect()->back()->withErrors(['msg' => 'amount is less than 1']);
        }
        $config = (object) [
            'iban' => $company->payze_iban,
            'amount' => $order->total,
            'userId' => $order->user_id

        ];
        $payment = new PayzeSplit($config);
        $success = $payment->paySplit($config);

        // create transaction
        $transactions = new Transaction();
        $transactions->user_id = $company->id;
        $transactions->pay_id = $success->payId;
        $transactions->order_id = $order->id;
        $transactions->valuta = 1;
        $transactions->total = $success->amount;
        $transactions->pay_method = 3;
        $transactions->transaction_status = 2;
        $transactions->full_name = $request->name;
        $transactions->number = $request->number;
        $transactions->lat = $request->lat;
        $transactions->lng = $request->lng;
        $transactions->address = $request->address;
        $transactions->email = $request->email;
        $transactions->id_number = $request->id_number;
        $transactions->special_code = $request->special_code;
        $transactions->save();

        if (!$success) {
            abort(404);
            return;
        }
        return redirect($success->redirect);
    }


    public function openBanking(Request $request, $frontId)
    {

        $order = Order::where('front_code', '=', $frontId)->first();
        $company = User::where('id', $order->user_id)->first();

        if ($order->total < 1) {
            return redirect()->back()->withErrors(['msg' => 'amount is less than 1']);
        }
        $config = (object) [
            'debtor_iban' => $company->payze_key,
            'creditor_iban' => $company->payze_key,
            'amount' => $order->total,
            'corrency' => 'GEL',
        ];
        $payment = new openBanking($config);
        $success = $payment->paymentRequest($config);

        // create transaction
        $transactions = new Transaction();
        $transactions->user_id = $company->id;
        $transactions->pay_id = $success['consentId'];
        $transactions->order_id = $order->id;
        $transactions->valuta = 1;
        $transactions->total = $order->total;
        $transactions->pay_method = 6;
        $transactions->transaction_status = 2;
        $transactions->full_name = $request->name;
        $transactions->number = $request->number;
        $transactions->lat = $request->lat;
        $transactions->lng = $request->lng;
        $transactions->address = $request->address;
        $transactions->email = $request->email;
        $transactions->id_number = $request->id_number;
        $transactions->special_code = $request->special_code;
        $transactions->save();

        if (!$success) {
            abort(404);
            return;
        }
        return redirect($success['location']);
    }



    public function iPay(Request $request, $frontId)
    {
        $order = Order::where('front_code', '=', $frontId)->first();
        $company = User::where('id', $order->user_id)->first();
        $api_config = (object) [
            'client_id' => $company->ipay_id,
            'secret_key' => $company->ipay_key
        ];
        $payment = new BOGpayment($api_config);
        $transaction = $payment->createOrder((object) $order);
        if (!$transaction) {
            abort(404);
            return;
        }
        $transactions = new Transaction();
        $transactions->user_id = $company->id;
        $transactions->pay_id = $transaction->orderid;
        $transactions->order_id = $order->id;
        $transactions->valuta = 1;
        $transactions->total = $order->total;
        $transactions->pay_method = 2;
        $transactions->transaction_status = 2;
        $transactions->full_name = $request->name;
        $transactions->number = $request->number;
        $transactions->lat = $request->lat;
        $transactions->lng = $request->lng;
        $transactions->address = $request->address;
        $transactions->email = $request->email;
        $transactions->id_number = $request->id_number;
        $transactions->special_code = $request->special_code;
        $transactions->save();

        return redirect($transaction->redirect);
    }
}
