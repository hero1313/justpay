<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\SmsUser;
use App\Models\Product;
use App\Payments\TBCpayment;
use App\Payments\BOGpayment;
use App\Payments\Payriffpayment;
use App\Traits\SendSMSTrait;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Payments\PayzePayment;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Redirect;
use App\Events\PusherBroadcast;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


use Exception;
use Throwable;

class TransactionController extends Controller
{
    use SendSMSTrait;

    public function index()
    {
        $limit = Carbon::now()->subDays(1);
        $transactions = Transaction::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('components.transactions', compact(['transactions','limit']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function refundOrder($pay_id)
    {
        $transaction = Transaction::find($pay_id);
        $user = User::find($transaction->user_id);
        if ($transaction->user_id == Auth::user()->id) {
            $transaction->pay_method;
            if ($transaction !== null) {

                // თიბისი თანხის დაბრუნება

                if ($transaction->pay_method == 1) {
                    $api_config = (object) [
                        'client_id' => Auth::user()->tbc_id,
                        'client_key' => Auth::user()->tbc_key
                    ];
                    $data = (object)[
                        'payId' => $transaction->pay_id,
                        'total' => $transaction->total
                    ];
                    $payment = new TBCpayment($api_config);
                    $trans = $payment->refund((object) $data);
                    $tbc_status = $payment->transactionStatus((object) $data);
                    $status = $tbc_status->status;
                }

                // საქართველოს ბანკი თანხის დაბრუნება

                else if ($transaction->pay_method == 2) {
                    $data = (object)[
                        'payId' => $transaction->pay_id,
                        'total' => $transaction->total
                    ];
                    $config = (object) [
                        'payId' => $transaction->pay_id,
                        'client_id' => $user->ipay_id,
                        'secret_key' => $user->ipay_key
                    ];

                    $payment = new BOGPayment($config);
                    $payze_refound = $payment->refund((object) $config);
                    $ipay_status = $payment->transactionStatus((object) $data);
                    $status = "Returned";
                    // $status = $ipay_status->status;

                }

                // ფეიზი თანხის დაბრუნება

                else if ($transaction->pay_method == 3) {
                    $data = (object)[
                        'payId' => $transaction->pay_id,
                        'total' => $transaction->total
                    ];
                    $config = (object) [
                        'payId' => $transaction->pay_id,
                        'apiKey' => $user->payze_id,
                        'apiSecret' => $user->payze_key
                    ];

                    $payment = new PayzePayment($config);
                    $payze_refound = $payment->refund((object) $config);
                    $payze_status = $payment->get((object) $config);
                    $payze_status = $payment->getOrderStatus((object) $data);
                    $status = $payze_status->status;
                }

                // ფეირიფი თანხის დაბრუნება

                else if ($transaction->pay_method == 5) {
                    $merchantUniqueNumber = $user->payriff_id;
                    $payriffGateway = new Payriffpayment;
                    $statusUrl = $payriffGateway->getStatusOrder(
                        'AZ',           // language
                        $transaction->pay_id,
                        $transaction->sessionId,
                        $merchantUniqueNumber
                    );
                    $refound = $payriffGateway->refund(
                        $transaction->total,
                        $transaction->pay_id,
                        $transaction->sessionId,
                        $merchantUniqueNumber
                    );

                    $status = $statusUrl;
                }
                if ($status == "Returned" || $status == "Refunded") {
                    $transaction->transaction_status = -1;
                }
                $transaction->update();
                return Redirect::to('https://onpay.cloud/transactions');
            }
        }
    }


    public function callback(Request $request, $id)
    {

        // $minutes = 2;
        // $response = new Response('Set Cookie');
        // $response->withCookie(cookie('name', 'MyValuew', $minutes));
        // dd(123);
        $transaction = Transaction::find($id);
        $user = User::find($transaction->user_id);
        $sms_users = SmsUser::where('company_user_id', '=', $user->id)->get();

        // თიბისი ქოლბექი 

        // საქართველოს ბანკი ქოლბექი 

        if ($transaction->pay_method == 2) {
            $data = (object)[
                'payId' => $transaction->pay_id,
                'total' => $transaction->total
            ];
            $config = (object) [
                'payId' => $transaction->pay_id,
                'client_id' => $user->ipay_id,
                'secret_key' => $user->ipay_key
            ];
            $payment = new BOGPayment($config);
            $ipay_status = $payment->transactionStatus((object) $data);
            $status = $ipay_status->status;

            if ($status === "success") {
                $transaction->transaction_status = 1;
                broadcast(new PusherBroadcast($transaction))->toOthers();
                if ($user->sms_name && $user->sms_token) {
                    $this->sendSMS($user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა', $user->id);
                    foreach ($sms_users as $sms_user) {
                        $this->sendSMS($sms_user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა', $user->id);
                    }
                }
            } else if ($status == "in_progress") {
                $transaction->transaction_status = 0;
            } else if ($status == "error" || $status == "Failed") {
                $transaction->transaction_status = -2;
            } else if ($status == "Returned") {
                $transaction->transaction_status = -1;
            }
        }

        // ფეირიფი ქოლბექი 

        else if ($transaction->pay_method == 5) {
            $merchantUniqueNumber = $user->payriff_id;
            $payriffGateway = new Payriffpayment;
            $statusUrl = $payriffGateway->getStatusOrder(
                'AZ',           // language
                $transaction->pay_id,
                $transaction->sessionId,
                $merchantUniqueNumber
            );
            $status = $statusUrl;

            if ($status === "APPROVED") {
                $transaction->transaction_status = 1;
                broadcast(new PusherBroadcast($transaction))->toOthers();
                if ($user->sms_name && $user->sms_token) {
                    $this->sendSMS($user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა ', $user->id);
                    foreach ($sms_users as $sms_user) {
                        $this->sendSMS($sms_user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა ', $user->id);
                    }
                }
            } else if ($status == "CREATED") {
                $transaction->transaction_status = 0;
            } else if ($status == "EXPIRED" || $status == "Failed") {
                $transaction->transaction_status = -2;
            } else if ($status == "RETURNED") {
                $transaction->transaction_status = -1;
            }
        }
        $transaction->update();

        return Redirect::to('https://justpay.ge');
    }

    public function redirect($id)
    {
        $transaction = Transaction::find($id);
        $user = User::find($transaction->user_id);
        $api_config = (object) [
            'client_id' => $user->tbc_id,
            'client_key' => $user->tbc_key
        ];
        $data = (object)[
            'payId' => $transaction->pay_id,
            'total' => $transaction->total
        ];
        $payment = new TBCpayment($api_config);
        $tbc_status = $payment->transactionStatus((object) $data);
        $status = $tbc_status->status;
        if ($status == "Expired" || $status == "Failed") {
            $transaction->transaction_status = -2;
        }

        $transaction->update();

        return Redirect::to('https://justpay.ge/');
    }

    public function show($id)
    {
        $transactions = Transaction::find($id);
        $order = Order::find($transactions->order_id);
        $products = Product::where('order_id', '=', $order->id)->get();
        return view('components.transaction_item', compact(['transactions', 'order', 'products']));
    }
    public function update()
    {
        $updateTransactions = Transaction::where('user_id',Auth::user()->id)->where('seen',0)->get();
        foreach($updateTransactions as $transaction){
            $transaction->seen = 1;
            $transaction->update();
        }
        return Redirect::to('https://onpay.cloud/transactions');
    }

    public function callbackUrl(Request $request, $userId)
    {
        Log::info($request);

        $transaction = Transaction::where('pay_id', $request->PaymentId)->first();
        $user = User::find($transaction->user_id);
        $sms_users = SmsUser::where('company_user_id', '=', $user->id)->get();

        // თიბისი ქოლბექი 

        if ($transaction->pay_method == 1) {
            $api_config = (object) [
                'client_id' => $user->tbc_id,
                'client_key' => $user->tbc_key
            ];
            $data = (object)[
                'payId' => $transaction->pay_id,
                'total' => $transaction->total
            ];
            $payment = new TBCpayment($api_config);
            $tbc_status = $payment->transactionStatus((object) $data);
            $status = $tbc_status->status;

            if ($status === "Succeeded") {
                broadcast(new PusherBroadcast($transaction->user_id))->toOthers();
                $transaction->transaction_status = 1;
                if ($user->sms_name && $user->sms_token) {
                    $this->sendSMS($user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა', $user->id);
                    foreach ($sms_users as $sms_user) {
                        $this->sendSMS($sms_user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა', $user->id);
                    }
                }
            } else if ($status == "Created") {
                $transaction->transaction_status = 0;
            } else if ($status == "Expired" || $status == "Failed") {
                $transaction->transaction_status = -2;
            } else if ($status == "Returned") {
                $transaction->transaction_status = -1;
            }
        }

        // საქართველოს ბანკი ქოლბექი 

        else if ($transaction->pay_method == 2) {
            $data = (object)[
                'payId' => $transaction->pay_id,
                'total' => $transaction->total
            ];
            $config = (object) [
                'payId' => $transaction->pay_id,
                'client_id' => $user->ipay_id,
                'secret_key' => $user->ipay_key
            ];
            $payment = new BOGPayment($config);
            $ipay_status = $payment->transactionStatus((object) $data);
            $status = $ipay_status->status;

            if ($status === "success") {
                $transaction->transaction_status = 1;
                broadcast(new PusherBroadcast($transaction))->toOthers();
                if ($user->sms_name && $user->sms_token) {
                    $this->sendSMS($user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა', $user->id);
                    foreach ($sms_users as $sms_user) {
                        $this->sendSMS($sms_user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა', $user->id);
                    }
                }
            } else if ($status == "in_progress") {
                $transaction->transaction_status = 0;
            } else if ($status == "error" || $status == "Failed") {
                $transaction->transaction_status = -2;
            } else if ($status == "Returned") {
                $transaction->transaction_status = -1;
            }
        }

        // ფეიზი ქოლბექი 

        else if ($transaction->pay_method == 3) {
            $status = $request->PaymentStatus;
            if ($status === "Captured") {
                broadcast(new PusherBroadcast($transaction))->toOthers();
                $transaction->transaction_status = 1;
                if ($user->sms_name && $user->sms_token) {
                    $this->sendSMS($user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა', $user->id);
                    foreach ($sms_users as $sms_user) {
                        $this->sendSMS($sms_user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა', $user->id);
                    }
                }
            } else if ($status == "Draft") {
                $transaction->transaction_status = 0;
            } else if ($status == "Blocked" || $status == "Rejected" || $status == "Timeout") {
                $transaction->transaction_status = -2;
            } else if ($status == "Refunded") {
                $transaction->transaction_status = -1;
            }
        }

        // ფეირიფი ქოლბექი 

        else if ($transaction->pay_method == 5) {
            $merchantUniqueNumber = $user->payriff_id;
            $payriffGateway = new Payriffpayment;
            $statusUrl = $payriffGateway->getStatusOrder(
                'AZ',           // language
                $transaction->pay_id,
                $transaction->sessionId,
                $merchantUniqueNumber
            );
            $status = $statusUrl;

            if ($status === "APPROVED") {
                $transaction->transaction_status = 1;
                broadcast(new PusherBroadcast($transaction))->toOthers();
                if ($user->sms_name && $user->sms_token) {
                    $this->sendSMS($user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა ', $user->id);
                    foreach ($sms_users as $sms_user) {
                        $this->sendSMS($sms_user->number, 'onpay.ge : თქვენს ანგარიშზე მოხდა პროდუქტის შესყიდვა ', $user->id);
                    }
                }
            } else if ($status == "CREATED") {
                $transaction->transaction_status = 0;
            } else if ($status == "EXPIRED" || $status == "Failed") {
                $transaction->transaction_status = -2;
            } else if ($status == "RETURNED") {
                $transaction->transaction_status = -1;
            }
        }
        $transaction->update();

        Log::info($transaction);

    }
}
