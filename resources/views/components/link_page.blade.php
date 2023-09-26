@extends('index')
@section('content')

    <div class="container">
        <h4 class="link-number">@lang('public.pay_link') #{{ $order->id }}</h4>
        <h6 class="link-desc">@lang('public.copy_and')</h6>
        <div class="order-link-div">
            <input class="link-input" type="text" value="https://onpay.cloud/order/{{ $front_code }}" id="linkInput">
            <button class="btn" onclick="linkFunction()"><svg width='24' height='24' viewBox='0 0 24 24'
                    xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                    <rect width='20' height='20' stroke='none' fill='#ffffff' opacity='0' />
                    <g transform="matrix(1.43 0 0 1.43 12 12)">
                        <path
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"
                            transform=" translate(-8, -7.5)"
                            d="M 2.5 1 C 1.675781 1 1 1.675781 1 2.5 L 1 10.5 C 1 11.324219 1.675781 12 2.5 12 L 4 12 L 4 12.5 C 4 13.324219 4.675781 14 5.5 14 L 13.5 14 C 14.324219 14 15 13.324219 15 12.5 L 15 4.5 C 15 3.675781 14.324219 3 13.5 3 L 12 3 L 12 2.5 C 12 1.675781 11.324219 1 10.5 1 Z M 2.5 2 L 10.5 2 C 10.78125 2 11 2.21875 11 2.5 L 11 10.5 C 11 10.78125 10.78125 11 10.5 11 L 2.5 11 C 2.21875 11 2 10.78125 2 10.5 L 2 2.5 C 2 2.21875 2.21875 2 2.5 2 Z M 12 4 L 13.5 4 C 13.78125 4 14 4.21875 14 4.5 L 14 12.5 C 14 12.78125 13.78125 13 13.5 13 L 5.5 13 C 5.21875 13 5 12.78125 5 12.5 L 5 12 L 10.5 12 C 11.324219 12 12 11.324219 12 10.5 Z"
                            stroke-linecap="round" />
                    </g>
                </svg>
            </button>
        </div>
        <div class="order-link-div">
            <input class="link-input" type="number" placeholder="ნომერი" id="sms_input">
            <button class="btn" id="sms_button">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#fff" class="bi bi-envelope"
                    viewBox="0 0 16 16">
                    <path
                        d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                </svg>
            </button>
        </div>

        <button style="opacity:0" id="downloadSVG">Download .svg</button>
        @if ($order->qr)
            <div class="qr d-block">
                <div class="dd mb-3">
                    @php
                        echo $order->qr;
                    @endphp
                </div>
                <button class="btn qr-btn" id="downloadPNG">Download QR</button>
            </div>
        @endif
        <div class="products-list">
            <h6 class="link-prod-title">@lang('public.products')</h6>
            @foreach ($products as $product)
                <div class="flex pay-order-div mb-3">
                    <img src="../assets/image/{{ $product->image }}" alt="">
                    <div>
                        <h6>{{ $product->ge_name }}</h6>
                        <h6 class="link-prod-price">
                            <h6 class="prices">{{ $product->price }}</h6>
                            {{ $product->price_discount }}
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
                @if ($product->price_discount)
                    <style>
                        .prices {
                            color: red;
                        }
                    </style>
                @endif
            @endforeach
        </div>
    </div>


    <script>
        $("#sms_button").click(function() {
            var number = $("#sms_input").val();
            $.ajax({
                type: 'get',
                url: '{{ url('/sms-link') }}',
                data: {
                    'number': number,
                    'order_id': {{ $order->id }}
                },
                success: function(response) {
                    Swal.fire(
                        'შეტყობინება წარმატებით გაიგზავნა!',
                        '',
                        'success'
                    )
                }
            })
        })

        function downloadSVGAsText() {
            const svg = document.querySelector('.dd svg');
            const base64doc = btoa(unescape(encodeURIComponent(svg.outerHTML)));
            const a = document.createElement('a');
            const e = new MouseEvent('click');
            a.download = 'download.svg';
            a.href = 'data:image/svg+xml;base64,' + base64doc;
            a.dispatchEvent(e);
        }

        function downloadSVGAsPNG(e) {
            const canvas = document.createElement("canvas");
            const svg = document.querySelector('.dd svg');
            const base64doc = btoa(unescape(encodeURIComponent(svg.outerHTML)));
            const w = parseInt(svg.getAttribute('width'));
            const h = parseInt(svg.getAttribute('height'));
            const img_to_download = document.createElement('img');
            img_to_download.src = 'data:image/svg+xml;base64,' + base64doc;
            console.log(w, h);
            img_to_download.onload = function() {
                console.log('img loaded');
                canvas.setAttribute('width', w);
                canvas.setAttribute('height', h);
                const context = canvas.getContext("2d");
                //context.clearRect(0, 0, w, h);
                context.drawImage(img_to_download, 0, 0, w, h);
                const dataURL = canvas.toDataURL('image/png');
                if (window.navigator.msSaveBlob) {
                    window.navigator.msSaveBlob(canvas.msToBlob(), "download.png");
                    e.preventDefault();
                } else {
                    const a = document.createElement('a');
                    const my_evt = new MouseEvent('click');
                    a.download = 'download.png';
                    a.href = dataURL;
                    a.dispatchEvent(my_evt);
                }
                //canvas.parentNode.removeChild(canvas);
            }
        }

        const downloadSVG = document.querySelector('#downloadSVG');
        downloadSVG.addEventListener('click', downloadSVGAsText);
        const downloadPNG = document.querySelector('#downloadPNG');
        downloadPNG.addEventListener('click', downloadSVGAsPNG);
    </script>
@stop
