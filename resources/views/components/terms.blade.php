@include('layouts.head')

<head>
    <title>@lang('public.invoice') {!! $company->name !!}</title>
    <meta property="og:title" content="@lang('public.invoice') - {!! $company->name !!}" />
    <script src="https://maps.googleapis.com/maps/api/js?key={!! env('GOOGLE_MAP_KEY') !!}&libraries=places"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<head>
    <style type="text/css">
        .menu, .hidden-nav, header {
        display: none
    }
    .contents{
        width: 100%;
    }
    h6{
        margin-top: 30px
    }
    </style>
</head>
<div class="container center">
    <h1 class="pt-4">წესები და პირობები</h1>
    <div class='row'>
        @if($order->lang == 'en')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->en_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'ge')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->ge_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'am')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->am_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'az')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->az_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'de')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->de_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'kz')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->kz_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'ru')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->ru_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'th')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->tj_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'tr')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->tr_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'ua')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->ua_terms!!}</h6>
        </div>
        @endif
        @if($order->lang == 'uz')
        <div class='col-md-12 col-12 '>
            <h6>{!!$company->uz_terms!!}</h6>
        </div>
        @endif
    </div>
</div>


@include('layouts.js')
