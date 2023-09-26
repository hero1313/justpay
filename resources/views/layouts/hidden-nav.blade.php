<div class="cover"></div>
<div class="hidden-nav">
    <div>
        <img class="x" src="../assets/img/x.svg" alt="">
    </div>
    <img class="nav-logo" src="../assets/img/logo.png" alt="">

    <a class="menu-item flex " href='/invoice'>
        <div class="flex item2 menu-product "><i class="fas fa-shopping-cart svg"></i><h6 class="product-name1">@lang('public.invoice')</h6></div>
    </a>
    <a class="menu-item flex "  href='{{ route('products.show') }}'>
        <div class="flex item2 menu-product "><i class="fas fa-tshirt svg"></i><h6 class="product-name1">@lang('public.products')</h6></div>
    </a>
    <a class="menu-item flex "  href='{{ route('order.show_table') }}'>
        <div class="flex item2 menu-orders "><i class="far svg fa-clock"></i> <h6 class="product-name1">@lang('public.invoices')</h6></div>
    </a>

    <div class="menu-item flex ">
        <a class="flex item2 menu-transaction "  href='{{ route('transactions.index') }}'><i class="fas fa-exchange-alt svg"></i> <h6 class="product-name1">@lang('public.transactions')</h6></a>
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
</div>
