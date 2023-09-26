<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;

use Illuminate\Http\Request;
use App\Models\SmsUser;
use App\Events\PusherBroadcast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\File;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        $SmsUser = SmsUser::where('company_user_id', '=', Auth::user()->id)->get();

        return view('components.setting', compact(['SmsUser']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

        $user = User::find(Auth::user()->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->number = $request->number;
        $user->identity_number = $request->identity_number;
        $user->address = $request->address;
        $user->bank_code = $request->bank_code;
        $user->account_number = $request->account_number;

        $user->tbc = $request->tbc;
        $user->payze = $request->payze;
        $user->payze_split = $request->payze_split;
        $user->payriff = $request->payriff;
        $user->ipay = $request->ipay;
        $user->stripe = $request->stripe;


        $user->tbc_id = $request->tbc_id;
        $user->payze_id = $request->payze_id;
        $user->stripe_id = $request->stripe_id;
        $user->ipay_id = $request->ipay_id;
        $user->payriff_id = $request->payriff_id;
        $user->tbc_key = $request->tbc_key;
        $user->payze_key = $request->payze_key;
        $user->payze_iban = $request->payze_iban;
        $user->stripe_key = $request->stripe_key;
        $user->ipay_key = $request->ipay_key;
        $user->payriff_key = $request->payriff_key;

        $user->sms_name = $request->sms_name;
        $user->sms_token = $request->sms_token;
        $user->open_banking_index = $request->open_banking_index;
        $user->open_banking_bog = $request->open_banking_bog;
        $user->open_banking_tbc = $request->open_banking_tbc;
        $user->gel = $request->gel;
        $user->usd = $request->usd;
        $user->euro = $request->euro;
        $user->lang_am = $request->lang_am;
        $user->lang_az = $request->lang_az;
        $user->lang_de = $request->lang_de;
        $user->lang_en = $request->lang_en;
        $user->lang_ge = $request->lang_ge;
        $user->lang_kz = $request->lang_kz;
        $user->lang_ru = $request->lang_ru;
        $user->lang_tj = $request->lang_tj;
        $user->lang_ua = $request->lang_ua;
        $user->lang_uz = $request->lang_uz;
        $user->lang_tr = $request->lang_tr;

        $user->en_terms = $request->en_terms;
        $user->ge_terms = $request->ge_terms;
        $user->am_terms = $request->am_terms;
        $user->az_terms = $request->az_terms;
        $user->de_terms = $request->de_terms;
        $user->kz_terms = $request->kz_terms;
        $user->ru_terms = $request->ru_terms;
        $user->tj_terms = $request->tj_terms;
        $user->tr_terms = $request->tr_terms;
        $user->ua_terms = $request->ua_terms;
        $user->uz_terms = $request->uz_terms;



        if($request->hasfile('logo')){
            $destination='assets/image/'.$user->profile_photo_path;
            if(File::exists($destination)){
                File::delete($destination);
            }
            $file = $request->file('logo');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('assets/image/',$filename);
            $user->profile_photo_path = "$filename";
        }

        $user->update();
        return redirect('/setting');
    }

    public function terms($orderId)
    {   
        $order = Order::find($orderId);
        $company = User::find($order->user_id);

        return view('components.terms', compact(['company','order']));
    }
}
