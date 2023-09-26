@extends('index')
@section('content')
    <div class="col-12 main-transaction">
        <div class="text-nowrap overflow-none">
            <div class="reports-filter row">
                <div class="col-12 col-md-6">
                    <form class="" action="">
                        <div class="flex">
                            <div>
                                <label for="">@lang('public.start_date')</label>
                                <input class="form-control" type="date" value="{{ $first_date }}" name="first_date">
                            </div>
                            <div>
                                <label for="">@lang('public.end_date')</label>
                                <input class="form-control" type="date" value="{{ $second_date }}" name="second_date">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">@lang('public.filter')</button>
                    </form>
                </div>
                <div class="col-12 col-md-3">
                    <button class="btn btn-success">@lang('public.income') : {{ $total }}</button>
                </div>
                <div class="col-12 col-md-3">
                    <div class="dropdown w-100">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('public.sort')
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <button class="dropdown-item display-invoice" type="button">@lang('public.as_invoices')</button>
                            <button class="dropdown-item display-product" type="display-product">@lang('public.as_products')</button>
                        </div>
                    </div>
                </div>
                <a href="/export"><button class="btn btn-success mt-4">@lang('public.export_excel')</button></a>

            </div>
            @foreach ($transactions as $transaction)
                @php
                    $invoices = DB::table('orders')
                        ->where('id', '=', $transaction->order_id)
                        ->get();
                    $products = DB::table('products')
                        ->where('order_id', '=', $transaction->order_id)
                        ->get();
                    
                @endphp

                <div class="products-report">
                    <div class="mb-3 reports-container">
                        @foreach ($products as $product)
                            <div class="flex pay-order-div reports-product mb-3">
                                <img src="../assets/image/{{ $product->image }}" alt="">
                                <div class="ml-3">
                                    <h6>{{ $product->ge_name }}</h6>
                                    <h6 class="link-prod-price">{{ $product->price }}
                                        @if ($product->currency == 1)
                                            GEL
                                        @elseif ($product->currency == 2)
                                            EURO
                                        @elseif ($product->currency == 3)
                                            USD
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach


            @foreach ($invoices_n as $invoice)
                @php
                    // აქ უნდა გავითვალისწინოთ რომ პირველ პირობაში
                    // ეს ტრანზაქვიები უნდა იყოს სტატუსით წარმატებული
                    // და მეორე პირობაშიც იგივე
                    $trans_count = DB::table('transactions')
                        ->where('order_id', '=', $invoice->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->where('transaction_status', '=', 1)
                        ->count();
                    $transactions = DB::table('transactions')
                        ->where('order_id', '=', $invoice->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->where('transaction_status', '=', 1)
                        ->get();
                    $products = DB::table('products')
                        ->where('order_id', '=', $invoice->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->get();
                    
                    if ($first_date) {
                        $trans_count = DB::table('transactions')
                            ->where('transaction_status', '=', 1)
                            ->where('order_id', '=', $invoice->id)
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('created_at', '>', $first_date)
                            ->count();
                        $transactions = DB::table('transactions')
                            ->where('transaction_status', '=', 1)
                            ->where('order_id', '=', $invoice->id)
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('created_at', '>', $first_date)
                            ->get();
                    }
                    if ($second_date) {
                        $trans_count = DB::table('transactions')
                            ->where('transaction_status', '=', 1)
                            ->where('order_id', '=', $invoice->id)
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('created_at', '<', $second_date)
                            ->count();
                        $transactions = DB::table('transactions')
                            ->where('transaction_status', '=', 1)
                            ->where('order_id', '=', $invoice->id)
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('created_at', '<', $second_date)
                            ->get();
                    }
                    if ($second_date && $first_date) {
                        $trans_count = DB::table('transactions')
                            ->where('transaction_status', '=', 1)
                            ->where('order_id', '=', $invoice->id)
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('created_at', '<', $second_date)
                            ->where('created_at', '>', $first_date)
                            ->count();
                        $transactions = DB::table('transactions')
                            ->where('transaction_status', '=', 1)
                            ->where('order_id', '=', $invoice->id)
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('created_at', '<', $second_date)
                            ->where('created_at', '>', $first_date)
                            ->get();
                    }
                @endphp

                @foreach ($transactions as $transaction)
                    <div class="invoice-report">
                        <div class="row invoice-row" data-toggle="collapse" data-target="#{{ $invoice->id }}"
                            aria-expanded="false" aria-controls="collapseExample">
                            <div class="col-12 col-md-4">{{ $invoice->name }}</div>
                            <div class=" col-12 col-md-2">{{ $invoice->total }}</div>
                            <div class=" col-12 col-md-1">
                                @if ($invoice->valuta == 1)
                                    GEL
                                @elseif ($invoice->valuta == 2)
                                    EURO
                                @elseif ($invoice->valuta == 3)
                                    USDs
                                @endif
                            </div>
                            <div class="col-5"><a
                                    href="/order/{{ $invoice->front_code }}">{{ $url }}/order/{{ $invoice->front_code }}</a>
                            </div>
                        </div>
                        <div class="collapse" id="{{ $invoice->id }}">
                            @foreach ($products as $product)
                                <div class="flex pay-order-div mb-3">
                                    <img src="../assets/image/{{ $product->image }}" alt="">
                                    <div class="ml-3">
                                        <h6>{{ $product->ge_name }}</h6>
                                        <h6 class="link-prod-price">{{ $product->price }}
                                            @if ($product->currency == 1)
                                                GEL
                                            @elseif ($product->currency == 2)
                                                EURO
                                            @elseif ($product->currency == 3)
                                                USD
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            @endforeach
                            @foreach ($transactions as $transaction)
                                <div class="row reports-transaction">
                                    <div class="col-12 col-md-6">
                                        <a href="transaction/{{ $transaction->id }}">{{ $transaction->pay_id }}</a>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        {{ $transaction->total }}
                                        @if ($transaction->valuta == 1)
                                            GEL
                                        @elseif ($transaction->valuta == 2)
                                            EURO
                                        @elseif ($transaction->valuta == 3)
                                            USD
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        @if ($transaction->pay_method == 1)
                                            TBC PAY
                                        @elseif ($transaction->pay_method == 2)
                                            IPAY
                                        @elseif ($transaction->pay_method == 3)
                                            STRIPE
                                        @elseif ($transaction->pay_method == 4)
                                            PAYZE
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        {{ $transaction->created_at }}
                                    </div>
                                    @if ($transaction->full_name)
                                        <div class="col-12 col-md-6">
                                            {{ $transaction->full_name }}
                                        </div>
                                    @endif
                                    @if ($transaction->number)
                                        <div class="col-12 col-md-6">
                                            {{ $transaction->number }}
                                        </div>
                                    @endif
                                    @if ($transaction->email)
                                        <div class="col-12 col-md-6">
                                            {{ $transaction->email }}
                                        </div>
                                    @endif
                                    @if ($transaction->id_number)
                                        <div class="col-12 col-md-6">
                                            {{ $transaction->id_number }}
                                        </div>
                                    @endif
                                    @if ($transaction->special_code)
                                        <div class="col-12 col-md-6">
                                            {{ $transaction->special_code }}
                                        </div>
                                    @endif
                                    @if ($transaction->address)
                                        <div class="col-12 col-md-6">
                                            {{ $transaction->address }}
                                        </div>
                                        <hr>
                                        <div class="col-6">
                                            <iframe
                                                src="https://maps.google.com/maps?q={{ $transaction->lat }},{{ $transaction->lng }}&t=&z=15&ie=UTF8&iwloc=&output=embed"></iframe>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            @endforeach

        </div>
    </div>
@stop
