<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use QrCode;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'shiping' => 'required|numeric',
        ]);

        $front_code = Str::random(8);
        $x = $request->amount;
        $quantity = $request->amount;
        $pr_price = $request->price;
        $priceDiscount = $request->price_discount;
        $total = 0;
        if ($request->amount != null) {
            for ($i = 0; $i < count($x); $i++) {
                if ($priceDiscount[$i]) {
                    $total = $total + ($quantity[$i] * $priceDiscount[$i]);
                } else {
                    $total = $total + ($quantity[$i] * $pr_price[$i]);
                }
            }
            $total = $total + $request->shiping;
            $order = new Order;
            if ($request->tbc != null || $request->ipay != null || $request->payze != null || $request->payze_iban != null || $request->stripe != null || $request->payriff != null || $request->open_banking != null) {
                $order->user_id = Auth::user()->id;
                $order->front_code = $front_code;
                $order->lang = $request->lang;
                $order->valuta = $request->currency;
                $order->total = $total;
                if ($request->qr) {
                    $qr = QrCode::size(120)
                        ->generate('https://onpay.cloud/order/' . $front_code);
                    $order->qr = $qr;
                }
                $order->name = $request->invoice_name;
                $order->shiping = $request->shiping;
                $order->full_name = $request->fullname;
                $order->number = $request->telephone;
                $order->address = $request->address;
                $order->email = $request->email;
                $order->id_number = $request->id_number;
                $order->special_code = $request->spec_code;
                $order->tbc = $request->tbc;
                $order->ipay = $request->ipay;
                $order->payze = $request->payze;
                $order->payze_split = $request->payze_split;
                $order->payriff = $request->payriff;
                $order->stripe = $request->stripe;
                $order->open_banking = $request->open_banking;
                $order->description = $request->customers_info;
                $order->save();

                // product store
                $product = new Product;
                $user_id = Auth::user()->id;
                $order_id = $order->id;
                $geName = $request->ge_name;
                $enName = $request->en_name;
                $amName = $request->am_name;
                $azName = $request->az_name;
                $deName = $request->de_name;
                $kzName = $request->kz_name;
                $ruName = $request->ru_name;
                $tjName = $request->tj_name;
                $trName = $request->tr_name;
                $uaName = $request->ua_name;
                $uzName = $request->uz_name;
                $amount = $request->amount;
                $price = $request->price;
                $priceDiscount = $request->price_discount;
                $currency = $request->valuta;
                $save = $request->save_index;
                $saved_image = $request->saved_image;
                for ($i = 0; $i < count($amount); $i++) {
                    $string = Str::random(20);
                    if (isset($request->image[$i])) {
                        $file = $request->image[$i];
                        $extention = $file->getClientOriginalExtension();
                        $filename = $string . '.' . $extention;
                        $file->move('assets/image/', $filename);
                        $product->image = $filename;

                        $datasave = [
                            'user_id' => $user_id,
                            'order_id' => $order_id,
                            'ge_name' => $geName[$i],
                            'en_name' => $enName[$i],
                            'am_name' => $amName[$i],
                            'az_name' => $azName[$i],
                            'de_name' => $deName[$i],
                            'kz_name' => $kzName[$i],
                            'ru_name' => $ruName[$i],
                            'tj_name' => $tjName[$i],
                            'tr_name' => $trName[$i],
                            'ua_name' => $uaName[$i],
                            'uz_name' => $uzName[$i],
                            'amount' => $amount[$i],
                            'price' => $price[$i],
                            'price_discount' => $priceDiscount[$i],
                            'currency' => $currency[$i],
                            'save_index' => $save[$i],
                            'image' => $filename,
                        ];
                    } else if ($saved_image) {
                        $product->image = '';
                        $filename = '';
                        $datasave = [
                            'user_id' => $user_id,
                            'order_id' => $order_id,
                            'ge_name' => $geName[$i],
                            'en_name' => $enName[$i],
                            'am_name' => $amName[$i],
                            'az_name' => $azName[$i],
                            'de_name' => $deName[$i],
                            'kz_name' => $kzName[$i],
                            'ru_name' => $ruName[$i],
                            'tj_name' => $tjName[$i],
                            'tr_name' => $trName[$i],
                            'ua_name' => $uaName[$i],
                            'uz_name' => $uzName[$i],
                            'amount' => $amount[$i],
                            'price' => $price[$i],
                            'price_discount' => $priceDiscount[$i],
                            'currency' => $currency[$i],
                            'save_index' => $save[$i],
                            'image' => $saved_image[$i],
                        ];
                    }
                    DB::table('products')->insert(($datasave));
                }

                return redirect('order_link/' . $front_code);
            } else {
                if (app()->getLocale() == 'ge') {
                    return redirect()->back()->withErrors(['msg' => 'გადახდის მეთოდი არჩეული არარის'])->withInput();
                } elseif (app()->getLocale() == 'en') {
                    return redirect()->back()->withErrors(['msg' => 'No payment method selected'])->withInput();
                }
            }
        } else {
            if (app()->getLocale() == 'ge') {
                return redirect()->back()->withErrors(['msg' => 'პროდუქტი არარის დამატებული']);;
            } elseif (app()->getLocale() == 'en') {
                return redirect()->back()->withErrors(['msg' => 'there are no products']);;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($front_code)
    {
        $order = Order::all()->where('front_code', '=', $front_code)->last();
        App::setLocale($order->lang);

        if ($order->valuta == 1)
            $currency = 'GEL';
        elseif ($order->valuta == 2)
            $currency = 'EUR';
        elseif ($order->valuta == 3)
            $currency = 'USD';

        $products = Product::all()->where('order_id', '=', $order->id);
        $company = User::find($order->user_id);
        $subtotal = $order->total - $order->shiping;
        return view('components.order_view', compact(['order', 'products', 'currency', 'subtotal', 'company']));
    }

    public function showTable()
    {
        $orders = Order::orderBy('created_at', 'DESC')->where('user_id', '=', Auth::user()->id)->paginate(30);
        $url = request()->getHost();
        return view('components.orders', compact(['orders', 'url']));
    }

    public function order_link($front_code)
    {
        $url = $url = request()->getHost();
        $order = Order::all()->where('front_code', '=', $front_code)->last();
        $products = Product::all()->where('order_id', '=', $order->id);
        return view('components.link_page', compact(['front_code', 'url', 'order', 'products']));
    }

    public function removeInvoice($id)
    {
        $invoice = Order::find($id);
        $invoice->delete();
        return redirect()->back();
    }
}
