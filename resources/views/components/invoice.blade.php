@extends('index')
@section('content')
    @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ $error }}',
                })
            </script>
        @endforeach
    @endif


    <form class="product-form" id="invoice_form" action='{{ route('store.order') }}' enctype='multipart/form-data'
        method="POST">
        @csrf
        <div class="col-12 main-product">
            <div class="row main-row">
                <div class="col-12   col-lg-4 pages">
                    <h5 class="product-name">@lang('public.add_product')</h5>
                    <div class="col-9  input-name2 ">
                        <button type="button" class="btn new-product-btn" data-toggle="modal"
                            data-target="#exampleModalCenter">
                            @lang('public.add_product')
                        </button>
                    </div>
                    <br>
                    <div class="form-group mt-3">
                        <h5 class="product-name" data-toggle="collapse" data-target="#name_collapse" aria-expanded="false"
                            aria-controls="collapseExample">@lang('public.invoice_name')<img src="../assets/img/down.svg"
                                alt=""></h5>
                        <input type="text" id="name_collapse" class="form-control invoice-name" required
                            name="invoice_name">
                    </div>
                    <div class="invoice_type">
                        <h5 class="product-name" data-toggle="collapse" data-target="#invoice_type_collapse"
                            aria-expanded="false" aria-controls="collapseExample">@lang('public.invoice_type') <img
                                src="../assets/img/down.svg" alt=""></h5>
                        <div class="collapse collapse-none" id="invoice_type_collapse">
                            <div class="radio-container col-11">
                                <input type="radio" id="inv_type1" name="inv_type" value="1" checked
                                    onchange='preview_products();'>
                                <label class="label-checkbox" for="inv_type1">@lang('public.one_time') </label><button
                                    class="btn btn-one d-none" data-toggle="tooltip" data-placement="top"
                                    title="აქ რასაც გვინდა ჭავწერთ">?</button>
                                <br>
                                <input type="radio" id="inv_typ2" name="inv_type" value="2"
                                    onchange='preview_products();'>
                                <label for="inv_type2">@lang('public.multi_order')</label><button class="btn btn-one d-none"
                                    data-toggle="tooltip" data-placement="top" title="აქ რასაც გვინდა ჭავწერთ">?</button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_type">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="qr" value="1">
                            <label class="label-checkbox " for="coding">QR code</label>
                        </div>
                    </div>
                    <div class="payment">
                        <h5 class="product-name " data-toggle="collapse" data-target="#requirements_collapse"
                            aria-expanded="false" aria-controls="collapseExample">@lang('public.payment_requirements')<img
                                src="../assets/img/down.svg" alt=""></h5>
                        <div class="collapse collapse-none" id="requirements_collapse">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="hs-1 form-check-input" name="fullname" value="1"
                                    id='req_fname_ch'>
                                <label class="label-checkbox " for="coding">@lang('public.full_name')</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox"class="hs-2 form-check-input" name="telephone" value="1"
                                    id='req_tel_ch'>
                                <label class="label-checkbox " for="coding">@lang('public.telephone')</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="hs-3 form-check-input" name="address" value="1"
                                    id='req_addr_ch'>
                                <label class="label-checkbox " for="coding">@lang('public.address')</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="hs-4 form-check-input" name="email" value="1"
                                    id='req_email_ch'>
                                <label class="label-checkbox " for="coding">@lang('public.email')</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="hs-5 form-check-input" name="id_number" value="1"
                                    id='req_id_ch'>
                                <label class="label-checkbox " for="coding">@lang('public.id_number')</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="hs-6 form-check-input" name="spec_code" value="1"
                                    id='req_code_ch'>
                                <label class="label-checkbox" for="coding">@lang('public.special_code')</label>
                            </div>
                        </div>
                    </div>
                    <div class="confirmation">
                        <h5 class="product-name" data-toggle="collapse" data-target="#curency_collapse"
                            aria-expanded="false" aria-controls="collapseExample">@lang('public.currency')<img
                                src="../assets/img/down.svg" alt=""></h5>
                        <div class="collapse collapse-none" id="curency_collapse">
                            <select class="form-select select-order select-price" aria-placeholder="price"
                                aria-label="Default select example" id='prod_valuta' name='currency'>
                                @if (Auth::user()->gel == 1)
                                    <option value="1">gel</option>
                                @endif
                                @if (Auth::user()->euro == 1)
                                    <option value="2">euro</option>
                                @endif
                                @if (Auth::user()->usd == 1)
                                    <option value="3">usd</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="confirmation">
                        <h5 class="product-name" data-toggle="collapse" data-target="#lang_collapse"
                            aria-expanded="false" aria-controls="collapseExample">invoice language<img
                                src="../assets/img/down.svg" alt=""></h5>
                        <div class="collapse collapse-none" id="lang_collapse">
                            <select class="form-select select-order select-price" aria-placeholder="price"
                                aria-label="Default select example"  name='lang'>
                                @if(Auth::user()->lang_ge == 1)
                                <option value="ge">ქართული</option>
                                @endif
                                @if(Auth::user()->lang_en == 1)
                                <option value="en">ინგლისური</option>
                                @endif
                                @if(Auth::user()->lang_am == 1)
                                <option value="am">სომხური</option>
                                @endif
                                @if(Auth::user()->lang_az == 1)
                                <option value="az">აზერბაიჯანული</option>
                                @endif
                                @if(Auth::user()->lang_de == 1)
                                <option value="de">დანიური</option>
                                @endif
                                @if(Auth::user()->lang_kz == 1)
                                <option value="kz">ყაზახური</option>
                                @endif
                                @if(Auth::user()->lang_ru == 1)
                                <option value="ru">რუსული</option>
                                @endif
                                @if(Auth::user()->lang_tj == 1)
                                <option value="tj">ტაჯიკური</option>
                                @endif
                                @if(Auth::user()->lang_uz == 1)
                                <option value="uz">უზბეკური</option>
                                @endif
                                @if(Auth::user()->lang_tr == 1)
                                <option value="tr">თურქული</option>
                                @endif
                                @if(Auth::user()->lang_ua == 1)
                                <option value="ua">უკრაინული</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <h5 class="product-name" data-toggle="collapse" data-target="#shiping_collapse"
                            aria-expanded="false" aria-controls="collapseExample">@lang('public.shiping_price')<img
                                src="../assets/img/down.svg" alt=""></h5>
                        <input type="number" id="shiping_collapse" class="form-control shiping" value="0"
                            name="shiping">
                    </div>
                    <div class="confirmation">
                        <h5 class="product-name" data-toggle="collapse" data-target="#payment_collapse"
                            aria-expanded="false" aria-controls="collapseExample">@lang('public.pay_method')<img
                                src="../assets/img/down.svg" alt=""></h5>
                        <div class="collapse collapse-none" id="payment_collapse">
                            @if (Auth::user()->tbc_id != null && Auth::user()->tbc_key != null && Auth::user()->tbc == 1)
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="tbc_checkbox" checked class="tbc-prev form-check-input"
                                        name="tbc" value="1">
                                    <label class="label-checkbox" for="coding">TBC</label>
                                </div>
                            @endif
                            @if (Auth::user()->payze_id != null && Auth::user()->payze_key != null && Auth::user()->payze == 1)
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="payze_checkbox" checked
                                        class="payze-prev form-check-input" name="payze" value="1">
                                    <label class="label-checkbox" for="coding">payze</label>
                                </div>
                            @endif
                            @if (Auth::user()->payze_iban != null && Auth::user()->payze_split == 1)
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="payze_iban_checkbox" checked
                                        class="payze-iban-prev form-check-input" name="payze_split" value="1">
                                    <label class="label-checkbox" for="coding">payze split</label>
                                </div>
                            @endif
                            @if (Auth::user()->payriff_id != null && Auth::user()->payriff_key != null && Auth::user()->payriff == 1)
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="payriff_checkbox" checked
                                        class="payriff-prev form-check-input" name="payriff" value="1">
                                    <label class="label-checkbox" for="coding">PAYRIFF</label>
                                </div>
                            @endif
                            @if (Auth::user()->ipay_id != null && Auth::user()->ipay_key != null && Auth::user()->ipay == 1)
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="ipay_checkbox" checked class="ipay-prev form-check-input"
                                        name="ipay" value="1">
                                    <label class="label-checkbox" for="coding">ipay</label>
                                </div>
                            @endif
                            @if (Auth::user()->stripe_id != null && Auth::user()->stripe_key != null && Auth::user()->stripe == 1)
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="stripe_checkbox" checked
                                        class="stripe-prev form-check-input" name="stripe" value="1">
                                    <label class="label-checkbox" for="coding">stripe</label>
                                </div>
                            @endif
                            @if (Auth::user()->open_banking_bog != null || Auth::user()->open_banking_tbc != null )
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="open_checkbox" checked class="open-prev form-check-input"
                                        name="open_banking" value="1">
                                    <label class="label-checkbox" for="open_checkbox">bank account</label>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="confirmation">
                        <h5 class="product-name" data-toggle="collapse" data-target="#confirmation_collapse"
                            aria-expanded="false" aria-controls="collapseExample">@lang('public.confirmation_page')<img
                                src="../assets/img/down.svg" alt=""></h5>
                        <div class="collapse collapse-non d-block form-check form-switch" id="confirmation_collapse">
                            <input type="checkbox" class="hs-7 form-check-input" name="customers_info" value="1">
                            <label class="label-checkbox " for="coding">@lang('public.colect')</label>
                        </div>
                    </div>
                </div>
                <!-- preview -->
                <div class="col-12 col-lg-8 pages1 ">
                    <h5 class="product-name">@lang('public.product')</h5>
                    <div class="flex previews row">
                        <div class="col-9 flex">
                            <a href="">
                                <h5 class="preview-pages">@lang('public.pay_page')</h5>
                            </a>
                        </div>
                        <div class="col-3 responsive-button">
                            <div class="  buttons flex">
                                <i class="fas fa-mobile-alt" id="mobile"></i>
                                <i class="fas fa-desktop " id="desktop"></i>
                            </div>
                        </div>
                    </div>
                    <div class="preview-order container sub-prev">
                        <div class="row">
                            <div class="col-12 col-md-7 products">
                                <section class="brand-section d-flex">
                                    <img src="../assets/image/{{ Auth::user()->profile_photo_path }}" alt="">
                                    <h4>{{ Auth::user()->name }}</h4>
                                </section>
                                <section class="preview-products">
                                    <div class="table preview-table mb-2">
                                        <div>
                                            <div class="preview-thead-tr d-flex">
                                                <div class="th-1">@lang('public.product')</div>
                                                <div class="th-2">@lang('public.quantity')</div>
                                                <div class="th-3">@lang('public.price')</div>
                                                <div class="th-0"></div>
                                            </div>
                                        </div>
                                        <div id="nano">
                                        </div>
                                    </div>
                                </section>
                                <section class="preview-total">
                                    <div class="d-flex">
                                        <h5 class="preview-subtotal">@lang('public.subtotal')</h5>
                                        <h5 class="total-price" id='total_str'></h5>
                                    </div>
                                    <div class="d-flex">
                                        <h5 class="preview-subtotal">@lang('public.shiping_price')</h5>
                                        <h5>
                                        </h5>
                                    </div>
                                    <hr>
                                    <div class="d-flex">
                                        <h4 class="preview-total-h4">@lang('public.total')</h4>
                                        <h4></h4>
                                    </div>

                                </section>
                            </div>
                            <div class="col-12 col-md-5 opt price1">
                                <h5 class="contact-info">@lang('public.contact_info')</h5>
                                <div class="opt-div opt-div-order row ">
                                    <div class="opt-div row">
                                        {{-- hidden filds --}}
                                        <div class="col-12 col-md-6 h-1 hidden-spec">
                                            <input type="text" placeholder="@lang('public.full_name')" class="form-control">
                                        </div>
                                        <div class="col-12 col-md-6 h-2 hidden-spec">
                                            <input type="text" placeholder="@lang('public.telephone')" class="form-control">
                                        </div>
                                        <div class="col-12 col-md-6 h-3 hidden-spec">
                                            <input type="text" placeholder="@lang('public.address')" class="form-control">
                                        </div>
                                        <div class="col-12 col-md-6 h-4 hidden-spec">
                                            <input type="text" placeholder="@lang('public.email')" class="form-control">
                                        </div>
                                        <div class="col-12 col-md-6 h-5 hidden-spec">
                                            <input type="text" placeholder="@lang('public.id_number')" class="form-control">
                                        </div>
                                        <div class="col-12 col-md-6 h-6 hidden-spec">
                                            <input type="text" placeholder="@lang('public.special_code')" class="form-control">
                                        </div>
                                        <div class="col-12  h-7 hidden-spec">
                                            <textarea class="form-control cust" rows="3" placeholder="@lang('public.customers_info')"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-block mt-4">
                                    <div class="bank-form flex w-100">
                                        <div class="d-block w-100">
                                            <h5 class="product-name pay-method center">@lang('public.pay')</h5>
                                            <div class="bank-form flex">
                                                @if (Auth::user()->tbc_id != null && Auth::user()->tbc_key != null)
                                                    <div id="tbc_prev" class="bank-item">
                                                        <img src="../assets/img/tbc.png" alt="">
                                                    </div>
                                                @endif
                                                @if (Auth::user()->payze_id != null && Auth::user()->payze_key != null)
                                                    <div id="payze_prev" class="bank-item">
                                                        <img src="../assets/img/payze.png" alt="">
                                                    </div>
                                                @endif
                                                @if (Auth::user()->payze_iban != null)
                                                    <div id="payze_iban_prev" class="bank-item">
                                                        <img src="../assets/img/payze.png" alt="">
                                                    </div>
                                                @endif
                                                @if (Auth::user()->payriff_id != null && Auth::user()->payriff_key != null)
                                                    <div id="payriff_prev" class="bank-item">
                                                        <img src="../assets/img/payriff.png" alt="">
                                                    </div>
                                                @endif
                                                @if (Auth::user()->ipay_id != null && Auth::user()->ipay_key != null)
                                                    <div id="ipay_prev" class="bank-item">
                                                        <img src="../assets/img/ipay.jpg" alt="">
                                                    </div>
                                                @endif
                                                @if (Auth::user()->stripe_id != null && Auth::user()->stripe_key != null)
                                                    <div id="stripe_prev" class="bank-item">
                                                        <img src="../assets/img/stripe.png" alt="">
                                                    </div>
                                                @endif
                                                @if (Auth::user()->open_banking_bog != null || Auth::user()->open_banking_tbc != null)
                                                    <div id="open_prev" class="bank-item">
                                                        <img src="../assets/img/open-banking.png" alt="">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="btn_pay" class=" btn btn-pay">@lang('public.create_invoice')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('components.add_product_modal')


    @if (Auth::user()->sms_name == null && Auth::user()->sms_token == null)
        <script>
            $("#req_tel_ch").click(function() {
                $('#req_tel_ch').prop('checked', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'ფუნქციის ჩასართავად გაააქტიურეთ SMS სერვისი ',
                })
            });
        </script>
    @else
        <script>
            $('.hs-2').change(function() {
                $('.h-2').toggle();
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            function imgError(image) {
                image.onerror = "";
                image.src = "/images/noimage.gif";
                return true;
            }
            $("#btn_pay").click(function() {
                if ($("#tbc_checkbox").is(":checked") || $("#payze_checkbox").is(":checked") || $("#payze_iban_checkbox").is(":checked") || $(
                        "#payriff_checkbox").is(":checked") || $(
                        "#stripe_checkbox").is(":checked") || $("#ipay_checkbox").is(":checked") || $(
                        "#open_checkbox").is(":checked")) {
                    $('#invoice_form').submit();
                    // alert('122');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '@lang('public.payment_error')',
                    })
                }
            });
        });
    </script>
@stop
