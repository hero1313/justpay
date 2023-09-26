<!DOCTYPE html>
<html>
@include('layouts.head')

<body>


    <header>
        <img class="burger" src="../assets/img/burger.svg" alt="">
    </header>
    @php
        $transactions = DB::table('transactions')
            ->where('user_id', '=', Auth::user()->id)
            ->where('seen', '=', 0)
            ->get();
        $countTransaction = $transactions->count();
        if(isset($countTransaction)){

        }
        else{
            $countTransaction = 0;
        }
    @endphp
    <div class="row">
        @include('layouts.hidden-nav')
        <div class="col col-sm-3  col-md-2  menu">
            @include('layouts.navbar')
        </div>
        <div class="col-12 col-lg-10  contents">
            @yield('content')
        </div>
    </div>
    @include('layouts.js')
</body>

</html>
