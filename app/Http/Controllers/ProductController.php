<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Events\PusherBroadcast;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        return Excel::download(new ReportExport, 'sales.xlsx');
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
    public function show(Request $request)
    {
        $search = $request->search;
        if(!$search){
            $products = Product::where('user_id', '=', Auth::user()->id)->where('save_index', '=', 1)->orderBy('created_at', 'DESC')->paginate(30);
        }
        else{
            $products = Product::where('user_id', '=', Auth::user()->id)->where('name', 'LIKE', "%$search%")->where('save_index', '=', 1)->orderBy('created_at', 'DESC')->paginate(30);
        }
        return view('components.products', compact(['products','search']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        $product = Product::find($id);
        $product->save_index = 0;
        $product->update();
        return back();
    }


    public function addProducts(Request $request)
    {
        if (!$request->currency || !$request->price) {
            return redirect()->back()->withInput($request->input())
                ->withErrors(['msg' => 'გთხოვთ შეავსოთ ყველა ველი']);
        }
        $product = new Product;
        $product->user_id = Auth::user()->id;
        $product->order_id = 0;
        $product->ge_name = $request->ge_name;
        $product->en_name = $request->en_name;
        $product->am_name = $request->am_name;
        $product->az_name = $request->az_name;
        $product->de_name = $request->de_name;
        $product->kz_name = $request->kz_name;
        $product->ru_name = $request->ru_name;
        $product->tj_name = $request->tj_name;
        $product->tr_name = $request->tr_name;
        $product->ua_name = $request->ua_name;
        $product->uz_name = $request->uz_name;
        $product->price = $request->price;
        $product->amount = 1;
        $product->currency = $request->currency;
        $product->code = $request->code;
        $product->save_index = 1;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('assets/image/', $filename);
            $product->image = "$filename";
        }

        $product->save();
        return back();
    }

    public function editProduct(Request $request, $id)
    {
        if (!$request->currency || !$request->price) {
            return redirect()->back()->withInput($request->input())
                ->withErrors(['msg' => 'გთხოვთ შეავსოთ ყველა ველი']);
        }
        $product = Product::find($id);
        $product->user_id = Auth::user()->id;
        $product->order_id = 0;
        $product->ge_name = $request->ge_name;
        $product->en_name = $request->en_name;
        $product->am_name = $request->am_name;
        $product->az_name = $request->az_name;
        $product->de_name = $request->de_name;
        $product->kz_name = $request->kz_name;
        $product->ru_name = $request->ru_name;
        $product->tj_name = $request->tj_name;
        $product->tr_name = $request->tr_name;
        $product->ua_name = $request->ua_name;
        $product->uz_name = $request->uz_name;
        $product->price = $request->price;
        $product->amount = 1;
        $product->currency = $request->currency;
        $product->code = $request->code;
        $product->save_index = 1;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('assets/image/', $filename);
            $product->image = "$filename";
        }

        $product->update();
        return back();
    }
}
