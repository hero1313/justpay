@extends('index')
@section('content')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/jquery.tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.tiny.cloud/1/im4wqk189hm6f7je34qdufei0cozkq8hay6vq8dbot7mak3q/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <form action='/payment-callback/1' enctype='multipart/form-data' method="POST">
        @csrf
        <button type="submit">submit</button>
    </form>

    <div class="col-12 main-order setting">
        <form action='{{ route('update.setting') }}' enctype='multipart/form-data' method="POST">
            @csrf
            @method('PUT')
            <div class="col col-12 main-bank">
                <div class="row pass1" style="margin: 20px 10.3%;">
                    <div class="col-12 center pass-change-name">@lang('public.parameters')</div>
                    <div class="col-12  col-md-6 input-name2 ">
                        <div class="textOnInput ">
                            <label for="inputText">@lang('public.name')</label>
                            <input class="form-control" type="text" id='uname' name='name'
                                value='{{ Auth::user()->name }}'>
                        </div>
                    </div>
                    <div class="col-12  col-md-6 input-name2 ">
                        <div class="textOnInput ">
                            <label for="inputText">@lang('public.email')</label>
                            <input class="form-control" type="text" id='email' name='email'
                                value='{{ Auth::user()->email }}'>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 input-name2 ">
                        <div class="textOnInput ">
                            <label for="inputText">@lang('public.telephone')</label>
                            <input class="form-control" type="text" id='tel' name='number'
                                value='{{ Auth::user()->number }}'>
                        </div>
                    </div>
                    <div class="col-12  col-md-6 input-name2 ">
                        <div class="textOnInput ">
                            <label for="inputText">@lang('public.identification_number')</label>
                            <input class="form-control" type="text" id='saident' name='identity_number'
                                value='{{ Auth::user()->identity_number }}'>
                        </div>
                    </div>
                    <div class="col-12  col-md-6 input-name2 ">
                        <div class="textOnInput ">
                            <label for="inputText">@lang('public.address')</label>
                            <input class="form-control" type="text" id='addr' name='address'
                                value='{{ Auth::user()->address }}'>
                        </div>
                    </div>
                    {{-- <div class="col-12  col-md-6 input-name2 ">
                            <div class="textOnInput ">
                            <label for="inputText">@lang('public.bank_code')</label>
                            <input class="form-control" type="text" id='bank_code' name='bank_code' value='{{Auth::user()->bank_code}}'>
                            </div>
                        </div>
                        <div class="col-12  col-md-6 input-name2 ">
                            <div class="textOnInput ">
                            <label for="inputText">@lang('public.account_number')</label>
                            <input class="form-control" type="text" id='bank_angarish' name='account_number' value='{{Auth::user()->account_number}}'>
                            </div>
                        </div> --}}
                    <div class="col-12  col-md-6 input-name2 ">
                        <div class="textOnInput ">
                            <label for="inputText">@lang('public.company_logo')</label> <br>
                            <input type="file" id='mlogo' name='logo'>
                            <img class="company-logo" src="../assets/image/{{ Auth::user()->profile_photo_path }}"
                                alt="">
                        </div>
                    </div>
                    <div class="col-12  col-md-6 input-name2 ">
                        <div class="textOnInput ">
                            <button class="btn btn-dark" type="submit">@lang('public.save')</button>
                        </div>
                    </div>
                    <div class="container-fluid mb-4">
                        <h3 class="font-weight-bold mt-3 ">@lang('public.currency')</h3>
                        <div class="flex ">
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="gel"
                                    <?php echo Auth::user()->gel == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    GEL
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="euro"
                                    <?php echo Auth::user()->euro == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    EURO
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="usd"
                                    <?php echo Auth::user()->usd == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    USD
                                </label>
                            </div>
                        </div>
                        <div class="col-12  col-md-6 input-name2 ">
                            <div class="textOnInput ">
                                <button class="btn btn-dark" type="submit">@lang('public.save')</button>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="container-fluid mb-4">
                        <h3 class="font-weight-bold mt-3 ">ენები</h3>
                        <div class="flex ">
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_en"
                                    <?php echo Auth::user()->lang_en == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    EN
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_ge"
                                    <?php echo Auth::user()->lang_ge == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    GE
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_az"
                                    <?php echo Auth::user()->lang_az == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    AZ
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_ru"
                                    <?php echo Auth::user()->lang_ru == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    RU
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_uz"
                                    <?php echo Auth::user()->lang_uz == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    UZ
                                </label>
                            </div>
                            {{-- <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_am"
                                    <?php echo Auth::user()->lang_am == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    AM
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_de"
                                    <?php echo Auth::user()->lang_de == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    DE
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_kz"
                                    <?php echo Auth::user()->lang_kz == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    KZ
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_tj"
                                    <?php echo Auth::user()->lang_tj == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    TJ
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_tr"
                                    <?php echo Auth::user()->lang_tr == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    TR
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input " type="checkbox" value="1" name="lang_ua"
                                    <?php echo Auth::user()->lang_ua == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    UA
                                </label>
                            </div> --}}
                        </div>
                        <div class="col-12  col-md-6 input-name2 ">
                            <div class="textOnInput ">
                                <button class="btn btn-dark" type="submit">@lang('public.save')</button>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="container-fluid mt-4 mb-4">
                        <h3 class="font-weight-bold mt-3 ">@lang('public.sms_office')</h3>
                        <div class='row'>
                            <div class='col-12 col-md-6'>
                                <div class='form-group'>
                                    <label for='tpay_userid'>@lang('public.name')</label>
                                    <input class='form-control' type='text' name='sms_name'
                                        value="{{ Auth::user()->sms_name }}">
                                </div>
                            </div>
                            <div class='col-md-6 col-12 '>
                                <div class='form-group'>
                                    <label for='tpay_secret'>@lang('public.token')</label>
                                    <input class='form-control' type='text' name='sms_token'
                                        value="{{ Auth::user()->sms_token }}">
                                </div>
                            </div>
                            <div class="col-12  col-md-6 input-name2 ">
                                <div class="textOnInput ">
                                    <button class="btn btn-dark" type="submit">@lang('public.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="container-fluid mt-4 mb-4">
                        <h3 class="font-weight-bold mt-3 ">@lang('public.payment')</h3>
                        <h5 class="mt-3 mb-3">ქოლბექი : https://onpay.cloud/api/payment-callback/{{Auth::user()->id}}</h5>
                        <div class="flex ">
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input tbc form-check-input" type="checkbox" value="1"
                                    name="tbc" <?php echo Auth::user()->tbc == 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    TBC
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input payzes form-check-input" type="checkbox" value="1"
                                    name="payze" <?php echo Auth::user()->payze == 1  ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    payze
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input payzes_split form-check-input" type="checkbox" value="1"
                                    name="payze_split"  <?php echo Auth::user()->payze_split == 1  ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    payze split
                                </label>
                            </div>
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input payriff form-check-input" type="checkbox" value="1"
                                    name="payze" <?php echo Auth::user()->payriff == 1  ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    payriff
                                </label>
                            </div>
                        </div>
                        <div class="flex ">
                            {{-- <div class="form-check pay-check form-switch">
                                <input class="form-check-input stripe form-check-input" type="checkbox" value="1"
                                    name="stripe" <?php echo Auth::user()->stripe == 1  ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Stripe
                                </label>
                            </div> --}}
                            <div class="form-check pay-check form-switch">
                                <input class="form-check-input ipay form-check-input" type="checkbox" value="1"
                                    name="ipay" <?php echo Auth::user()->ipay == 1  ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Ipay
                                </label>
                            </div>
                            @if (Auth::user()->open_banking_show == 1)
                                <div class="form-check pay-check form-switch">
                                    <input class="form-check-input open-banking form-check-input" type="checkbox"
                                        value="1" name="open_banking_index" <?php echo Auth::user()->open_banking_index == 1 ? ' checked' : ''; ?>>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Bank Account
                                    </label>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class='mb-4 full hide' id='tpay_div'>
                                <div class="note note-primary ">
                                    <h6 class="font-weight-bold">TBC Checkout</h6>
                                    <hr>
                                    <div class='row'>
                                        <div class='col-12 col-md-6'>
                                            <div class='form-group'>
                                                <label for='tpay_userid'>Tpay Client ID</label>
                                                <input class='form-control' type='text' name='tbc_id'
                                                    value="{{ Auth::user()->tbc_id }}" id='tpay_client_id'>
                                            </div>
                                        </div>
                                        <div class='col-md-6 col-12 '>
                                            <div class='form-group'>
                                                <label for='tpay_secret'>TPay Secret</label>
                                                <input class='form-control' type='text' name='tbc_key'
                                                    value="{{ Auth::user()->tbc_key }}" id='tpay_secret'>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='mb-4 full hide' id='payz_div'>
                                <div class="note note-light">
                                    <h6 class="font-weight-bold">Payze Checkout</h6>
                                    <hr>
                                    <div class='row'>
                                        <div class='col-12 col-md-6'>
                                            <div class='form-group'>
                                                <label for='payz_api_key'>Payze Api-Key</label>
                                                <input class='form-control' type='text' name='payze_id'
                                                    id='payz_api_key' value="{{ Auth::user()->payze_id }}">
                                            </div>
                                        </div>
                                        <div class='col-md-6 col-12 '>
                                            <div class='form-group'>
                                                <label for='bank_acc'>Payze api-secret</label>
                                                <input class='form-control' type='text' name='payze_key'
                                                    id='payz_api_secret' value="{{ Auth::user()->payze_key }}"></td>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <div class='mb-4 full ' id='payze_split_div'>
                                <div class="note note-light">
                                    <h6 class="font-weight-bold">Payze Split</h6>
                                    <hr>
                                    <div class='row'>
                                        <div class='col-12 col-md-6'>
                                            <div class='form-group'>
                                                <label for='payz_api_key'>ანგარიშის ნომერი</label>
                                                <input class='form-control' type='text' name='payze_iban'
                                                    id='payz_api_iban' value="{{ Auth::user()->payze_iban }}">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <div class='mb-4 full hide' id='stripe_div'>
                                <div class="note note-primary">
                                    <h6 class="font-weight-bold">Stripe Details</h6>
                                    <hr>
                                    <div class='row'>
                                        <div class='col-12 col-md-6'>
                                            <div class='form-group'>
                                                <label for='bank_acc'>Stripe Public Key</label>
                                                <input class='form-control' type='text' name='stripe_id'
                                                    id='stripe_public_key' value="{{ Auth::user()->stripe_id }}">
                                            </div>
                                        </div>
                                        <div class='col-md-6 col-12 '>
                                            <div class='form-group'>
                                                <label for='bank_acc'>Stripe Secret Key</label>
                                                <input class='form-control' type='text' name='stripe_key'
                                                    id='stripe_secret_key' value="{{ Auth::user()->stripe_key }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='mb-4 full hide' id='payriff_div'>
                                <div class="note note-primary ">
                                    <h6 class="font-weight-bold">Payriff</h6>
                                    <hr>
                                    <div class='row'>
                                        <div class='col-12 col-md-6'>
                                            <div class='form-group'>
                                                <label for='tpay_userid'>payriff merchant number</label>
                                                <input class='form-control' type='text' name='payriff_id'
                                                    value="{{ Auth::user()->payriff_id }}" id='payriff_client_id'>
                                            </div>
                                        </div>
                                        <div class='col-md-6 col-12 '>
                                            <div class='form-group'>
                                                <label for='tpay_secret'>payriff merchant secret</label>
                                                <input class='form-control' type='text' name='payriff_key'
                                                    value="{{ Auth::user()->payriff_key }}" id='payriff_secret'>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='mb-4 full hide' id='ipay_div'>
                                <div class="note note-primary">
                                    <h6 class="font-weight-bold">Ipay Details</h6>
                                    <hr>
                                    <div class='row'>
                                        <div class='col-12 col-md-6'>
                                            <div class='form-group'>
                                                <label for='ipay_client_id'>Ipay Client ID</label>
                                                <input class='form-control' type='text' name='ipay_id'
                                                    id='ipay_client_id' value="{{ Auth::user()->ipay_id }}">
                                            </div>
                                        </div>
                                        <div class='col-md-6 col-12 '>
                                            <div class='form-group'>
                                                <label for='ipay_secret_key'>Ipay Secret Key</label>
                                                <input class='form-control' type='text' name='ipay_key'
                                                    id='ipay_secret_key' value="{{ Auth::user()->ipay_key }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='mb-4 full hide' id='open_banking_div'>
                                <div class="note note-primary">
                                    <h6 class="font-weight-bold">ანგარიშის ნომრით გადახდა</h6>
                                    <hr>
                                    <div class='row'>
                                        <div class='col-12 col-md-6'>
                                            <div class='form-group'>
                                                <label for='open_banking_bog'>საქართველოს ანაგრიშის ნომერი</label>
                                                <input class='form-control' type='text' name='open_banking_bog'
                                                    id='open_banking_bog' value="{{ Auth::user()->open_banking_bog }}">
                                            </div>
                                        </div>
                                        <div class='col-md-6 col-12 '>
                                            <div class='form-group'>
                                                <label for='open_banking_tbc'>tbc ანაგრიშის ნომერი</label>
                                                <input class='form-control' type='text' name='open_banking_tbc'
                                                    id='open_banking_tbc' value="{{ Auth::user()->open_banking_tbc }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12  col-md-6 input-name2 ">
                        <div class="textOnInput ">
                            <button class="btn btn-dark" type="submit">@lang('public.save')</button>
                        </div>
                    </div>
                    <hr>

                    <div class="container-fluid mt-4 mb-4 terms">
                        <h3 class="font-weight-bold mt-3 ">@lang('public.sms_office')</h3>
                        <div class='row'>
                            @if (Auth::user()->lang_en == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები EN</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='en_terms'>{{ Auth::user()->en_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_ge == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები GE</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='ge_terms'>{{ Auth::user()->ge_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_am == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები AM</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='am_terms'>{{ Auth::user()->am_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_az == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები AZ</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='az_terms'>{{ Auth::user()->az_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_de == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები DE</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='de_terms'>{{ Auth::user()->de_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_kz == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები KZ</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='kz_terms'>{{ Auth::user()->kz_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_ru == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები RU</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='ru_terms'>{{ Auth::user()->ru_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_tj == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები TJ</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='tj_terms'>{{ Auth::user()->tj_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_tr == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები TR</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='tr_terms'>{{ Auth::user()->tr_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_ua == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები UA</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='ua_terms'>{{ Auth::user()->ua_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->lang_uz == 1)
                                <div class='col-md-12 col-12 '>
                                    <div class='form-group'>
                                        <label for='tpay_secret'>წესები და პირობები UZ</label>
                                        <textarea class="tiny" rows="4" cols="50" class='form-control' name='uz_terms'>{{ Auth::user()->uz_terms }}</textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-12  col-md-6 input-name2 ">
                            <div class="textOnInput ">
                                <button class="btn btn-dark" type="submit">@lang('public.save')</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        @if (Auth::user()->sms_token && Auth::user()->sms_name)
            <div class="col col-12">

                <div class="row pass1" style="margin: 20px 10.3%;">
                    <h3 class="font-weight-bold mt-3 mb-3">@lang('public.add_sms_number')</h3>

                    <button type="button" class="btn btn-primary mb-4" style="width: max-content" data-toggle="modal"
                        data-target="#newSmsUser">
                        @lang('public.add_number')
                    </button>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"> @lang('public.id')</th>
                                <th scope="col"> @lang('public.name')</th>
                                <th scope="col"> @lang('public.number')</th>
                                <th scope="col"> @lang('public.delete')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($SmsUser as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->number }}</td>
                                    <td>
                                        <form id="delete-form" method="POST"
                                            action="sms-user/delete/{{ $user->id }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button class="btn btn-danger" type="submit">@lang('public.delete')</button>
                                        </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="newSmsUser" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action='{{ route('store.new_sms_user') }}' method="post">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">@lang('public.add_sms_number')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">@lang('public.name')</label>
                                        <input type="text" name="user_name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">@lang('public.number')</label>
                                        <input type="number" name="user_number" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">@lang('public.close')</button>
                                    <button type="submit" class="btn btn-primary">@lang('public.add_number')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>
    <script>
        $('textarea.tiny').tinymce({    });
      </script>
@stop
