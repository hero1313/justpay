<!DOCTYPE html>
<html>
    @include('layouts.head')
<body>
    <header>
        <img class="burger" src="../assets/img/burger.svg" alt="">
    </header>
    <div class="row">
        @include('admin.layouts.hidden-nav')
        <div class="col col-sm-3  col-md-2  menu" >
             @include('admin.layouts.navbar')
        </div>
        <div class="col-12 col-lg-10  contents" >
            @yield('content')
       </div>
    </div>
    @include('layouts.js')
</body>
</html>


