<div class="cover"></div>
<div class="hidden-nav">
    <div>
        <img class="x" src="../assets/img/x.svg" alt="">
    </div>
    <a class="menu-item flex " href='{{ route('admin.merchants') }}'>
        <div class="flex item2 menu-product "><i class="fas fa-shopping-cart svg"></i><h6 class="product-name1">Merchants</h6></div>
    </a>
    <a class="menu-item flex "  href='{{ route('admin.orders') }}'>
        <div class="flex item2 menu-product "><i class="fas fa-tshirt svg"></i><h6 class="product-name1">Invoices</h6></div>
    </a>
    <a class="menu-item flex "  href='{{ route('admin.transactions') }}'>
        <div class="flex item2 menu-orders "><i class="fas fa-exchange-alt svg"></i><h6 class="product-name1">Transaction</h6></div>
    </a>
    <a class="menu-item flex "  href='{{ route('admin.language') }}'>
        <div class="flex item2 menu-orders "><i class="fas fa-exchange-alt svg"></i><h6 class="product-name1">language</h6></div>
    </a>
    <div class="menu-item flex ">
        <a class="flex item2 menu-transaction "  href='/logout'><i class="far svg fa-clock"></i> <h6 class="product-name1">Logout</h6></a>
    </div>

</div>
