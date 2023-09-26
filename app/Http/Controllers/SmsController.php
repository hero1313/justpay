<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\SendSMSTrait;
use Carbon\Carbon;
use App\Models\SmsUser;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SmsController extends Controller
{

	use SendSMSTrait;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function numberVerification(Request $request, $id)
	{
		$number = $request->number;
		$company_id = Order::find($id)->user_id;
		$randomNumber = random_int(1000, 9999);
        $this->sendSMS($number, $randomNumber, $company_id);
		
		return $randomNumber;

    }

	public function smsLink(Request $request)
	{
		$order = Order::find($request->order_id);
		if(Auth::user()->id == $order->user_id){
			$number = $request->number;
			$company_id = Auth::user()->id;
			$this->sendSMS($number, 'გადახდის ლინკი: https://onpay.cloud/order/'. $order->front_code, $company_id);
		}
    }

	public function newSmsUser(Request $request)
    {
        $user = new SmsUser;
		$user->company_user_id = Auth::user()->id;
        $user->name = $request->user_name;
		$user->number = $request->user_number;
        $user->save();
		return redirect('/setting');
    }


	public function deleteUserSms($id)
    {
        $user = SmsUser::find($id);
		$user->delete();
		return redirect('/setting');
    }
}
