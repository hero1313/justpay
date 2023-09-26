@include('layouts.head')

<head>
    <title>@lang('public.invoice') {{ $company->name }}</title>
    <meta property="og:title" content="@lang('public.invoice') - {{ $company->name }}" />
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</head>

<head>
    <style type="text/css">
        #map {
            width: 100%;
            height: 300px;
        }
        .terms-div{

            width: 100vw;
            height: max-content;
            padding: 10px 0;
            background: black;
            color: #fff;
            bottom: 0;
            padding-top: 13px
        }

        .terms-div h6{
            color: #fff
        }
    </style>
</head>
<div class="preview-order container">
    <div class="row">
        <div class="col-12 col-md-6">
            <section class="brand-section d-flex">
                <img src="../assets/image/{{ $company->profile_photo_path }}" alt="">
                <h4>{{ $company->name }}</h4>
            </section>
            <section class="preview-products">
                <table class="table preview-table">
                    <thead>
                        <tr class="preview-thead-tr">
                            <th>@lang('public.product')</th>
                            <th>@lang('public.quantity')</th>
                            <th>@lang('public.price')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr class="preview-tr">
                            <td>
                                <div class="prod-img d-flex">
                                    <img src="../assets/image/{{ $product->image }}" alt="">
                                    @if($order->lang == 'ge')
                                    <h6>{{ $product->ge_name }}</h6>
                                    @elseif($order->lang == 'en')
                                    <h6>{{ $product->en_name }}</h6>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <h6 class="preview-quantity">{{ $product->amount }}</h6>
                            </td>
                            <td>
                                @if($product->price_discount )
                                <h6 class="discounted">{{ $product->price_discount }}</h6> <h6>{{ $product->price }}</h6>
                                @else
                                <h6>{{ $product->price }}</h6>
                                @endif
                                    @if ($product->currency == 1)
                                    @lang('public.gel')
                                    @elseif ($product->currency == 2)
                                    @lang('public.euro')
                                    @elseif ($product->currency == 3)
                                    @lang('public.usd')
                                    @endif
                                </h6>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            <section class="preview-total">
                <div class="d-flex">
                    <h5 class="preview-subtotal">@lang('public.subtotal')</h5>
                    <h5>{{ $subtotal }} {{ $currency }}</h5>
                </div>
                <div class="d-flex">
                    <h5 class="preview-subtotal">@lang('public.shiping_price')</h5>
                    <h5>
                        @if ($order->shiping != 0)
                        {{ $order->shiping }} {{ $currency }}
                        @else
                        @lang('public.free')
                        @endif
                    </h5>
                </div>
                <hr>
                <div class="d-flex">
                    <h4 class="preview-total-h4">@lang('public.total')</h4>
                    <h4>{{ $order->total }} {{ $currency }}</h4>
                </div>
            </section>
        </div>
        <div class="col-12 col-md-6">
            <form id="sub">
                @if($order->full_name == 1 || $order->address == 1 ||  $order->email == 1 || $order->id_number == 1 || $order->special_code == 1 || $order->description == 1 || $order->number == 1)
                <h5 class="contact-info">@lang('public.contact_info')</h5>
                @endif
                <div class="opt-div opt-div-order row ">
                    @if ($order->full_name == 1)
                    <div class="col-12 col-md-6">
                        <input type="text" placeholder="@lang('public.full_name')" name='name' class="  form-control">
                    </div>
                    @endif

                    @if ($order->address == 1)
                    <div class="col-12 col-md-6" style=" padding: 15px 10px;">
                        <button type="button" style="width:100%" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalmap">
                            @lang('public.pin_map')
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="modalmap" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">@lang('public.pin_map')
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="container mt-3 pb-3">
                                        <div class="flex">
                                            <input id="pac-input" class="form-control" name="address"
                                                class="controls loc-input" type="text" placeholder="Search Box" />
                                            <button class="btn btn-primary " id="custom_btn" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="map"></div>
                                        <button type="button" class="btn btn-primary mt-4" data-dismiss="modal"
                                            aria-label="Close">
                                            @lang('public.select')
                                        </button>
                                    </div>

                                    {{--
                                    <script
                                        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initAutocomplete&libraries=places&v=weekly"
                                        defer></script> --}}

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($order->email == 1)
                    <div class="col-12 col-md-6">
                        <input type="text" name="email" placeholder="@lang('public.email')" class=" form-control">
                    </div>
                    @endif

                    @if ($order->id_number == 1)
                    <div class="col-12 col-md-6">
                        <input type="text" placeholder="@lang('public.id_number')" name="id_number"
                            class="  form-control">
                    </div>
                    @endif

                    @if ($order->special_code == 1)
                    <div class="col-12 col-md-6">
                        <input type="text" placeholder="@lang('public.special_code')" name="special_code"
                            class="  form-control">
                    </div>
                    @endif

                    @if ($order->description == 1)
                    <div class="col-12 ">
                        <textarea class="form-control textarea-order" rows="3"
                            placeholder="@lang('public.customers_info')"></textarea>
                    </div>
                    @endif
                    @if ($order->number == 1)
                    <div class="col-12 col-md-6">
                        <input type="text" placeholder="@lang('public.number')" id="number" name='number'
                            class="  form-control">
                        <button type="button" style="width: 100%" id="sms_btn"
                            class="btn btn-primary">@lang('public.send_code')</button>
                    </div>
                    <input class="d-none rand-value" type="number" placeholder="ჩაწერეთ სმს კოდი" id="random_value">
                    <input type="hidden" id="verran">
                    @endif
                    <input type="hidden" id="lat" name='lat' class="  form-control">
                    <input type="hidden" id="lng" name='lng' class="  form-control">
                </div>
                <div class="d-block mt-4">
                    <h5 class="contact-info mb-4">@lang('public.pay')</h5>
                    <div class="bank-form flex">
                        @if ($order->tbc == 1)
                        <div class="form-check">
                            <input class="form-check-input bank-checkbox mt-4" value='1' name='payment_index' type="radio"
                                id="tbc_checkbox">
                            <label class="form-check-label" for="tbc_checkbox">
                                <div id="tbc_prev" class="bank-item-order">
                                    <img src="../assets/img/tbc.png" alt="">
                                </div>
                            </label>
                        </div>
                        @endif
                        @if ($order->ipay == 1)

                        <div class="form-check">
                            <input class="form-check-input bank-checkbox mt-4" value='2' name='payment_index' type="radio"
                                id="ipay_checkbox">
                            <label class="form-check-label" for="ipay_checkbox">
                                <div id="ipay_prev" class="bank-item-order">
                                    <img src="../assets/img/ipay.jpg" alt="">
                                </div>
                            </label>
                        </div>
                        @endif
                        
                        @if ($order->payze_split == 1)
                        <div class="form-check">
                            <input class="form-check-input bank-checkbox mt-4" value='4' name='payment_index' type="radio"
                                id="payze_split_checkbox">
                            <label class="form-check-label" for="payze_split_checkbox">
                                <div id="payze_split_prev" class="bank-item-order">
                                    <img src="../assets/img/payze.png" alt="">
                                </div>
                            </label>
                        </div>
                        @endif
                        @if ($order->payze == 1)
                        <div class="form-check">
                            <input class="form-check-input bank-checkbox mt-4" value='4' name='payment_index' type="radio"
                                id="payze_checkbox">
                            <label class="form-check-label" for="payze_checkbox">
                                <div id="payze_prev" class="bank-item-order">
                                    <img src="../assets/img/payze.png" alt="">
                                </div>
                            </label>
                        </div>
                        @endif

                        @if ($order->payriff == 1)
                        <div class="form-check">
                            <input class="form-check-input bank-checkbox mt-4" value='5' name='payment_index' type="radio"
                                id="payriff_checkbox">
                            <label class="form-check-label" for="payriff_checkbox">
                                <div id="tbc_prev" class="bank-item-order">
                                    <img src="../assets/img/payriff.png" alt="">
                                </div>
                            </label>
                        </div>
                        @endif

                        @if ($order->open_banking == 1)
                        <div class="form-check">
                            <input class="form-check-input bank-checkbox mt-4" value='6' name='payment_index' type="radio"
                                id="open_checkbox">
                            <label class="form-check-label" for="open_checkbox">
                                <div id="open_prev" class="bank-item-order">
                                    <img src="../assets/img/open-banking.png" alt="">
                                </div>
                            </label>
                        </div>
                        @endif

                        {{-- @if ($order->stripe == 1)
                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="pk_test_51LTMwVFPefbRqaCagR6ohCK2mT45sLAIluvxN3Ej03DNce9A8cx3Zy3ZkUS7sPW0982St2YKoq5STOcg4UwUen0V00r7gDDlu7"
                            data-name="T-shirt" data-description="Comfortable cotton t-shirt" data-amount="500"
                            data-currency="usd" data-label="Subscribe">
                            </script>
                        @endif
                        @if ($order->payze == 1)
                        <a href="">
                            <div id="payze_prev" class="bank-item-order">
                                <img src="../assets/img/payze.png" alt="">
                            </div>
                        </a>
                        @endif
                         --}}
                    </div>
                    <div>
                        <button type="button" class="btn btn-pay">@lang('public.pay')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="center terms-div mt-4"><a href="/terms-conditions/{{$order->id}}"> <h6>terms and conditions</h6></a></div>

@include('layouts.js')
<script>
    $(".bank-checkbox").first().attr('checked', true);
    $("#sms_btn").click(function () {
        if ($("#number").val() != '') {
            var number = $("#number").val();
            $.ajax({
                type: 'get',
                dataType: "json",
                url: '{{url("sms")}}' + '/' + '{{$order->id}}',
                data: 'number=' + number,
                success: function (data) {
                    $('#verran').val(data)
                    $('.rand-value').removeClass('d-none');
                    $("#sms_btn").text("თავიდან გაგზავნა");
                }

            })
        } else (
            Swal.fire({
                icon: 'error',
                title: 'შეცდომა...',
                text: 'გთხოვთ შეიყვანოთ ნომერი',
            })
        )
    })
</script>

@if ($order->number == 1)
<script>
    $(".btn-pay").click(function () {

        if ($('#number').last().val() == '') {
            Swal.fire({
                icon: 'error',
                title: 'შეცდომა...',
                text: 'გთხოვთ შეიყვანოთ ნომერი',
            })
        } else if ($('#verran').last().val() == '') {
            Swal.fire({
                icon: 'error',
                title: 'შეცდომა...',
                text: 'გთხოცთ გააკეთოთ ნომრის ვერიფიცირება',
            })
        } else {
            if ($('#verran').last().val() == $('#random_value').last().val()) {
                $("#sub").submit();

            } else (
                Swal.fire({
                    icon: 'error',
                    title: 'შეცდომა...',
                    text: 'კოდი არასწორია',
                })
            )
        }

        if($('#ipay_checkbox').attr("checked", "checked")){
        }

        if($('#ipay_checkbox').is(':checked')) { alert("it's checked"); }
    })
</script>
@else
<script>
    $(".btn-pay").click(function () {
        if($('.form-check-input').is(':checked')) {
            $("#sub").submit();
        }
        else{
            Swal.fire({
                icon: 'error',
                title: 'შეცდომა...',
                text: 'გთხოვთ აირჩიეთ გადახდის მეთოდი',
            })
        }
    })
</script>
@endif
@if($errors->any())
<script>

    swal("{{$errors->first()}}", "", "error");

</script>
@endif
<script>
    if ($('#tbc_checkbox').is(':checked')) {
        $('#sub').attr('action', "{{ route('tbcPayment', ['frontId' => $order->front_code]) }}");
    }
    if ($('#payriff_checkbox').is(':checked')) {
        $('#sub').attr('action', "{{ route('payriffPayment', ['frontId' => $order->front_code]) }}");
    }
    if ($('#payze_checkbox').is(':checked')) {
        $('#sub').attr('action', "{{ route('payzePayment', ['frontId' => $order->front_code]) }}");
    }
    if ($('#payze_split_checkbox').is(':checked')) {
        $('#sub').attr('action', "{{ route('payzeSplitPayment', ['frontId' => $order->front_code]) }}");
    }
    if ($('#ipay_checkbox').is(':checked')) {
        $('#sub').attr('action', "{{ route('ipay', ['frontId' => $order->front_code]) }}");
    }
    if ($('#open_checkbox').is(':checked')) {
        $('#sub').attr('action', "{{ route('openBanking', ['frontId' => $order->front_code]) }}");
    }

    $("#tbc_checkbox").change(function () {
        $('#sub').attr('action', "{{ route('tbcPayment', ['frontId' => $order->front_code]) }}");
    })
    $("#payriff_checkbox").change(function () {
        $('#sub').attr('action', "{{ route('payriffPayment', ['frontId' => $order->front_code]) }}");
    })
    $("#payze_checkbox").change(function () {
        $('#sub').attr('action', "{{ route('payzePayment', ['frontId' => $order->front_code]) }}");
    })
    $("#payze_split_checkbox").change(function () {
        $('#sub').attr('action', "{{ route('payzeSplitPayment', ['frontId' => $order->front_code]) }}")
    })
    $("#ipay_checkbox").change(function () {
        $('#sub').attr('action', "{{ route('ipay', ['frontId' => $order->front_code]) }}");
    })
    // ოფენ ბანკინგი
    $("#open_checkbox").change(function () {
        $('#sub').attr('action', "{{ route('openBanking', ['frontId' => $order->front_code]) }}");
    })
</script>


<script>
    var map, marker, infowindow, geocoder;

    function initMap(lat, lng) {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 42.053372,
                lng: 44.170532
            },
            zoom: 7
        });

        // ---------------------------------
        infoWindow = new google.maps.InfoWindow();

        const locationButton = document.getElementById('custom_btn');

        locationButton.addEventListener("click", () => {
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        map.setCenter(pos);
                        console.log(pos.lat)
                        map.panTo(new google.maps.LatLng(pos.lat, pos.lng));
                        setPlaceOnMap(pos.lat, pos.lng);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        });

        // ------------------------------

        geocoder = new google.maps.Geocoder();

        var input = document.getElementById('pac-input');

        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        autocomplete.setFields(
            ['address_components', 'geometry', 'icon', 'name', 'place_id']);

        infowindow = new google.maps.InfoWindow();
        marker = new google.maps.Marker({
            map: map
        });



        autocomplete.addListener('place_changed', function () {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            setPlaceOnMap(place.geometry.location.lat(), place.geometry.location.lng());
            marker.setVisible(true);
            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }
        });

        $(".search-location").click(function () {
            $(".search-location").last().click()
            $(".search-location").last().click(function () {
            })
        });

        marker.setVisible(true);


        map.addListener('click', function (e) {
            var lat = e.latLng.lat();
            var lng = e.latLng.lng();
            map.panTo(new google.maps.LatLng(lat, lng));
            setPlaceOnMap(lat, lng);
        });

        if (lat && lng) {
            setPlaceOnMap(lat, lng);
            //map.setZoom(12);
        }


    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(
            browserHasGeolocation ?
                "Error: The Geolocation service failed." :
                "Error: Your browser doesn't support geolocation."
        );
        infoWindow.open(map);
    }

    function setPlaceOnMap(lat, lng) {

        $('#lat').val(lat);
        $('#lng').val(lng);

        geocoder
            .geocode({
                location: {
                    lat: lat,
                    lng: lng
                }
            })
            .then((response) => {
                if (response.results[1]) {
                    var place = response.results[1];
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(12);
                    }
                    marker.setPosition(place.geometry.location);
                    $('#pac-input').val(place.formatted_address);
                    if (place.address_components) {
                        var short_names = [];
                        var long_names = [];
                        for (var i = 0; i < place.address_components.length; i++) {
                            short_names.push(place.address_components[i].short_name);
                            long_names.push(place.address_components[i].long_name);
                            if (place.address_components[i].types[0] == 'locality') {
                                $("#city").val(place.address_components[i].long_name);
                            }
                            if (place.address_components[i].types[0] == 'country') {
                                $("#country").val(place.address_components[i].long_name);
                            }
                        }

                        var detailed_info = "<p><strong>მისამართი: </strong>" + long_names.join(", ") + "<p>";
                        detailed_info += "<p><strong>გრძედი: </strong>" + place.geometry.location.lat() + "<p>";
                        detailed_info += "<p><strong>განედი: </strong>" + place.geometry.location.lng() + "<p>";
                        $("#map_address").val(long_names.join(", "));
                    }
                } else {
                    window.alert("No results found");
                }


            })
    }

    $(function () {

        var lat = parseFloat($("#latitude").val());
        var lng = parseFloat($("#longitude").val());

        initMap(lat, lng);


        $(document).on('keyup keypress keydown', '#pac-input', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });

    })
</script>