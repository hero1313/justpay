<img class="nav-logo" src="../assets/img/logo.png" alt="">

<a class="menu-item flex " href='../invoice'>
    <div class="flex item2 menu-product "><i class="fas fa-shopping-cart svg"></i><h6 class="product-name1">@lang('public.invoice')</h6></div>
</a>
<a class="menu-item flex "  href='{{ route('products.show') }}'>
    <div class="flex item2 menu-product "><i class="fas fa-tshirt svg"></i><h6 class="product-name1">@lang('public.products')</h6></div>
</a>
<a class="menu-item flex "  href='{{ route('order.show_table') }}'>
    <div class="flex item2 menu-orders "><i class="far svg fa-clock"></i> <h6 class="product-name1">@lang('public.invoices')</h6></div>
</a>

<div class="menu-item flex ">
    <form action="/transactions-post" class="w-100" method="post">
        @csrf
        <button class="w-100  transaction-btn" type="submit">        
            <i class="fas fa-exchange-alt svg"></i> <h6 class="product-name1">@lang('public.transactions')</h6>
        </button>
    </form>
    {{-- <a class="flex item2 menu-transaction "  href='{{ route('transactions.index') }}'><i class="fas fa-exchange-alt svg"></i> <h6 class="product-name1">@lang('public.transactions')</h6></a> --}}
    <div class="transaction-count">
        {{ $countTransaction  ? $countTransaction : 0 }}
    </div>
</div>
<div class="menu-item flex ">
    <a class="flex item2 menu-transaction "  href='{{ route('reports.index') }}'><i class="fas fa-exchange-alt svg"></i> <h6 class="product-name1">@lang('public.report')</h6></a>
</div>
<div class="menu-item flex ">
    <a class="flex item2 menu-parameter " href='/setting'><i class="fas fa-cogs svg"></i> <h6 class="product-name1">@lang('public.settings')</h6></a>
</div>
<a class="menu-item flex " href='/logout'>
    <div class="flex item2  menu-login" ><i class="far fa-user svg"></i> <h6 class="product-name1">@lang('public.Logout')</h6></div>
</a>
<br>
<div class="dropdown ml-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ Config::get('app.locale') }}
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="locale/en">en</a>
        <a class="dropdown-item" href="locale/ge">ge</a>
        <a class="dropdown-item" href="locale/az">az</a>
        <a class="dropdown-item" href="locale/am">arm</a>
        <a class="dropdown-item" href="locale/de">ger</a>
        <a class="dropdown-item" href="locale/kz">kz</a>
        <a class="dropdown-item" href="locale/ru">ru</a>
        <a class="dropdown-item" href="locale/tj">tj</a>
        <a class="dropdown-item" href="locale/tr">tr</a>
        <a class="dropdown-item" href="locale/ua">ua</a>
        <a class="dropdown-item" href="locale/uz">uz</a>
    </div>
</div>


{{-- <form class="product-form" action='/open-banking' enctype='multipart/form-data'
    method="POST">
    @csrf
    <button type="submit">
            submit
    </button>
</form>  --}}