<?php

namespace App\Http\Controllers;

use App\Models\transaction;
use App\Models\image;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * 
     */

     
    public function index(Request $request){
        $invoices_n = DB::table('orders')->where('user_id', '=', Auth::user()->id)->get();
        $transactions = DB::table('transactions')->where('user_id', '=', Auth::user()->id)->where('transaction_status', '=',  1)->get();
        $first_date = $request->first_date;
        $second_date = $request->second_date;
        if ($request->first_date != null) {
            $transactions = DB::table('transactions')->where('user_id', '=', Auth::user()->id)->where('created_at', '>', $first_date)->get();
        }
        if ($request->second_date) {
            $transactions = DB::table('transactions')->where('user_id', '=', Auth::user()->id)->where('created_at', '<', $second_date)->get();
        }
        if ($request->second_date != null && $request->first_date != null) {
            $transactions = DB::table('transactions')->where('user_id', '=', Auth::user()->id)->where('created_at', '<', $second_date)->where('created_at', '>', $first_date)->get();
        }
        $url = request()->getHost();
        $total = 0;
        foreach ($transactions as $t) {
            $total = $total + $t->total;
        }
        return view('components.reports', compact(['invoices_n', 'url', 'total', 'first_date', 'second_date', 'transactions']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $image = new image;
        $image->random_number = $request->random_number;
        if ($request->hasfile('image')) {
            $destination = 'assets/image/' . $image->img;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('assets/image/', $filename);
            $image->img = "$filename";
        }
        $image->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $products = Product::where('user_id', '=', Auth::user()->id)->where('save_index', '=', 1)->orderBy('created_at', 'DESC')->paginate(10);
        return view('components.products', compact(['products']));
    }
}
