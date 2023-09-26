@extends('index')
@section('content')
<form action="/opan-banking-pay" method="post">
    @csrf
    <div class="bank-container">
        <div class="row bank-accounts-row ">
            <h2>აირჩიეთ ანგარიში</h2>
            @foreach ($accounts as $account)
                <div class="col-12 col-md-6 ">
                    <div class="bank-div">
                        <div class="form-check">
                            {{-- <input class="form-check-input " type="radio" onclick="checkBank({{ $loop->index }})" name="default">
                            <label class="form-check-label" onclick="checkBank({{ $loop->index }})" >{{$account['iban']}}</label>
                            <input class="form-check-input bank-radio" type="checkbox" value="{{$account['iban']}}" id="checkbox_iban_{{ $loop->index }}" name="iban">
                            <label for=""></label> --}}

                            <input class="form-check-input bank-radio" type="radio" value="{{ $account['iban'] }}"
                                id="checkbox_iban_{{ $loop->index }}" name="iban">
                            <label for="checkbox_iban_{{ $loop->index }}">{{ $account['iban'] }}</label>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="center">
                <button class="btn btn-main">გაგრძელება</button>
            </div>
        </div>
    </div>
</form>

    <style>
        .bank-accounts-row h2{
            text-align: center;
            margin-bottom: 50px;
        }
        .btn-main{
        background: #fd9157;
        padding: 10px 20px;
        color: #fff;
        font-size: 20px;
        margin-top: 50px;
        }

        .bank-container{
            width: 100vw;
            padding-top: 10vh;

        }
        body{
            background: #f4f4f4;
        }
        .bank-div label{
            margin-bottom: 0px;
        }
        .center{
            text-align: center;
        }
        .menu {
            display: none;
        }

        .bank-accounts-row {
            width: 50%;
            margin: auto;
            background-color: rgb(255, 255, 255);
            min-width: 800px;
            border-radius: 20px;
            padding: 40px 50px;
            box-shadow: 0px 0px 10px 0px rgba(202,202,202,0.75);
        }

        .bank-div {
            padding: 10px;
            background: #ff600a;
            border-radius: 10px;
            color: #fff;
            margin-bottom: 20px;
            font-size: 20px;
            margin-right: 10px;
            margin-left: 10p
        }
    </style>

     {{-- <script>
    function checkBank($id) {
        $('.bank-radio').prop( "checked", false )
        $('#checkbox_iban_' + $id).prop( "checked", true )
        $('#checkbox_resource_' + $id).prop( "checked", true )
    }
</script> --}}

@stop
