<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Payments\openBanking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('user_id', '=', Auth::user()->id)->where('save_index', '=', 1)->orderBy('created_at', 'DESC')->get();
        return view('components.invoice', compact(['products']));
    }

    public function openBankingRedirect(Request $request)
    {
        $payment = new openBanking();
        $success = $payment->accessToken();
        $acc = $payment->getAccoutns();
        $account = $acc['accounts'];
        $accounts = array_filter($account, function ($item) {
            return $item["currency"] == "GEL";
        });
        return view('components.open-redirect', compact(['accounts']));
        // dd($request);
    }
    public function pay(Request $request)
    {
        $payment = new openBanking();
        $success = $payment->pay($request->iban);
        return view('components.landing')->with('successMsg','Property is updated .');;       
    }
    public function addProductsAjax(Request $request)
    {
       $prod_id = $request->id;
       return  $data = Product::where('id', '=', $prod_id)->get();
    }
}
