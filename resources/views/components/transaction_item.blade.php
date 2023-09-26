@extends('index')
@section('content')

    <div class="container">
        <h4 class="link-number">@lang('public.transaction') #{{$order->id}}</h4>
        <div class="products-list">
            <h6 class="link-prod-title">@lang('public.products')</h6>
            @foreach ($products as $product)
                <div class="flex pay-order-div mb-3">
                    <img src="../assets/image/{{$product->image}}" alt="">
                    <div>
                        <h6>{{$product->ge_name}}</h6>
                        <h6 class="link-prod-price">{{$product->price}}
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
    <script>
        function myFunction() {
        var copyText = document.getElementById("myInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        }
    </script>
@stop
