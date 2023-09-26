@extends('index')
@section('content')
    <div class="col-12main-transaction" style="overflow-x: auto;">
        <div class="table-responsive text-nowrap ">
            <table class="table table-transaction ">
                <thead>
                    <tr class="order-tr">
                        <th scope="col">@lang('public.name')</th>
                        <th scope="col">@lang('public.number')</th>
                        <th scope="col">@lang('public.total')</th>
                        <th scope="col">@lang('public.currency')</th>
                        <th scope="col">@lang('public.pay_method')</th>
                        <th scope="col">@lang('public.pay_id')</th>
                        <th scope="col">@lang('public.create_date')</th>
                        <th scope="col">@lang('public.status')</th>
                        <th scope="col">@lang('public.action')</th>
                    </tr>
                </thead>
                @if (!empty($transactions) && $transactions->count())
                    @foreach ($transactions as $transaction)
                        <tbody class="body-table">
                            <td>{{ $transaction->full_name }}</td>
                            <td>{{ $transaction->number }}</td>
                            <td>{{ $transaction->total }}</td>
                            <td>
                                @if ($transaction->valuta == 1)
                                    GEL
                                @elseif ($transaction->valuta == 2)
                                    EURO
                                @elseif ($transaction->valuta == 3)
                                    USDs
                                @endif
                            </td>
                            <td>
                                @if ($transaction->pay_method == 1)
                                    TBC PAY
                                @elseif ($transaction->pay_method == 2)
                                    IPAY
                                @elseif ($transaction->pay_method == 4)
                                    STRIPE
                                @elseif ($transaction->pay_method == 3)
                                    PAYZE
                                @endif

                            </td>
                            <td> <a href="transaction/{{ $transaction->id }}">{{ $transaction->pay_id }}</a> </td>
                            <td>{{ $transaction->created_at }}</td>
                            {{-- <td>{{ $transaction->transaction_status }}</td> --}}
                            <td>
                                @if ($transaction->transaction_status == 1)
                                    <button class="btn btn-success"> @lang('public.paid')</button>
                                @elseif ($transaction->transaction_status == 2)
                                    <!-- <a href="repeatcallback/{{ $transaction->id }}">
                                        <button class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                                <path
                                                    d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                            </svg>
                                        </button>
                                    </a> -->
                                    <button class="btn btn-danger">@lang('public.discontinued')</button>
                                @elseif ($transaction->transaction_status == -1)
                                    <button class="btn btn-info"> @lang('public.returned')</button>
                                @elseif ($transaction->transaction_status == 0)
                                    <button class="btn btn-danger">@lang('public.discontinued')</button>
                                @elseif ($transaction->transaction_status == -2)
                                    <button class="btn btn-danger"> @lang('public.Rejected')</button>
                                @endif
                            </td>
                            <td>
                                @if ($transaction->transaction_status == 1 && $transaction->created_at > $limit)
                                    <form action="{{ route('transaction.cancel', ['pay_id' => $transaction->id]) }}"
                                        method="post">
                                        @csrf
                                        <button class="btn btn-primary" type="submit"> @lang('public.refund')</button>
                                    </form>
                                @endif
                            </td>
                        </tbody>
                    @endforeach
                    <tr>
                        <td colspan="10">
                            <div>{!! $transactions->appends(Request::all())->links() !!}</div>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="10">There are no data.</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
@stop
