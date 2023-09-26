@extends('index')
@section('content')
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">@lang('public.create_date')</th>
                <th scope="col">@lang('public.name')</th>
                <th scope="col">@lang('public.price')</th>
                <th scope="col">@lang('public.invoice_link')</th>
                <th scope="col">@lang('public.status')</th>
                <th></th>
                <th scope="col">@lang('public.action')</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($orders) && $orders->count())
                @foreach ($orders as $order)
                    <tr class="products-tr">
                        <th scope="row">{{ $order->created_at }}</th>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->total }}</td>
                        <td><a href="/order/{{ $order->front_code }}">{{ $url }}/order/{{ $order->front_code }}</a>
                        </td>
                        <td>
                            @if ($order->status == -1)
                                <button type="button" class="btn btn-danger">@lang('public.canceled')</button>
                            @elseif($order->status == 0)
                                <button type="button" class="btn btn-primary">@lang('public.active')</button>
                            @elseif($order->status == 1)
                                <button type="button" class="btn btn-success">@lang('public.payed')</button>
                            @endif
                        </td>
                        <td>
                            <div class="order-link-div d-flex">
                                <input class="link-input" type="number" placeholder="ნომერი" id="sms_input_{{$order->id}}">
                                <button class="btn ml-2" id="sms_button" onclick="sendSms({{$order->id}})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#00000"
                                        class="bi bi-envelope" viewBox="0 0 16 16">
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td>
                            <form id="delete-form" action="remove-invoice/{{ $order->id }}" method="POST">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger" type="submit">@lang('public.delete')</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="10">
                        <div>{!! $orders->appends(Request::all())->links() !!}</div>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="10">There are no data.</td>
                </tr>
            @endif

        </tbody>
    </table>
    <script>
        function sendSms($id) {
            var number = $("#sms_input_"+ $id).val();
            $.ajax({
                type: 'get',
                url: '{{ url('/sms-link') }}',
                data: {
                    'number': number,
                    'order_id': $id
                },
                success: function(response) {
                    Swal.fire(
                        'შეტყობინება წარმატებით გაიგზავნა!',
                        '',
                        'success'
                    )
                }
            })

        }
    </script>
@stop
