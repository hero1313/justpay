@extends('index')
@section('content')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-12 mb-4">
                <button type="button" class="btn btn-dark mb-4" data-toggle="modal" data-target="#addProductModal">
                    @lang('public.add_product')
                </button>
            </div>
            <div class="col-md-3 col-12 mb-4">
                <form action="/products" class="d-flex" method="get">
                    <input type="text" value="{{ $search ? $search : '' }}" class="form-control search"
                        placeholder="search" name='search'>
                    <button class="btn btn-primary ml-3">search</button>
                </form>
            </div>
        </div>
    </div>

    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">@lang('public.id')</th>
                <th scope="col">@lang('public.image')</th>
                <th scope="col">@lang('public.name')</th>
                <th scope="col">@lang('public.code')</th>
                <th scope="col">@lang('public.price')</th>
                <th scope="col">curency</th>
                <th scope="col">@lang('public.description')</th>
                <th scope="col">@lang('public.edit')</th>
                <th scope="col">@lang('public.delete')</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($products) && $products->count())
                @foreach ($products as $product)
                    <tr class="products-tr">
                        <th scope="row">{{ $product->id }}</th>
                        <td><img src="assets/image/{{ $product->image }}" alt=""></td>
                        <td>{{ $product->en_name }}</td>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->price }}</td>
                        <td>
                            @if($product->currency == 1)
                                GEl
                            @else
                                USD
                            @endif
                        </td>
                        <td>{{ $product->description }}</td>
                        <td>
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#edit_product_{{ $product->id }}">@lang('public.edit')</button>
                        </td>
                        <td>
                            <form id="delete-form" action="remove-save-product/{{ $product->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-danger" type="submit">@lang('public.delete')</button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="edit_product_{{ $product->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="addProductModal" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form action="edit-product/{{ $product->id }}" method="POST"
                                    enctype='multipart/form-data'>
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="add">
                                            <h5 class="product-name order-product">@lang('public.add_new')</h5>
                                            <h6 class="pre-text">@lang('public.provide')</h6>
                                            <hr>
                                            <div class="row order-info">
                                                <div class="col-12 col-lg-6">
                                                    @if (Auth::user()->lang_en == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') EN
                                                            </h5>
                                                            <input type="text" value="{{ $product->en_name }}"
                                                                name='en_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_ge == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') GE
                                                            </h5>
                                                            <input type="text" value="{{ $product->ge_name }}"
                                                                name='ge_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_am == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') AM
                                                            </h5>
                                                            <input type="text" value="{{ $product->am_name }}"
                                                                name='am_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_az == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') AZ
                                                            </h5>
                                                            <input type="text" value="{{ $product->az_name }}"
                                                                name='az_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_de == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') DE
                                                            </h5>
                                                            <input type="text" value="{{ $product->de_name }}"
                                                                name='de_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_kz == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') KZ
                                                            </h5>
                                                            <input type="text" value="{{ $product->kz_name }}"
                                                                name='kz_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_ru == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') RU
                                                            </h5>
                                                            <input type="text" value="{{ $product->ru_name }}"
                                                                name='ru_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_tj == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') TJ
                                                            </h5>
                                                            <input type="text" value="{{ $product->tj_name }}"
                                                                name='tj_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_ua == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') UA
                                                            </h5>
                                                            <input type="text" value="{{ $product->ua_name }}"
                                                                name='ua_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_uz == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') UZ
                                                            </h5>
                                                            <input type="text" value="{{ $product->uz_name }}"
                                                                name='uz_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    @if (Auth::user()->lang_tr == 1)
                                                        <div>
                                                            <h5 class="product-name order-product">@lang('public.product') TR
                                                            </h5>
                                                            <input type="text" value="{{ $product->tr_name }}"
                                                                name='tr_name' class="form-control order-input prod-name">
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h5 class="product-name order-product">@lang('public.code')</h5>
                                                        <input type="text" name='code'
                                                            value="{{ $product->code }}"
                                                            class="form-control order-input prod-name">
                                                    </div>
                                                    <div>
                                                        <h5 class="product-name order-product">@lang('public.price')</h5>
                                                        <div class="flex price-4">
                                                            <input type="number" value="{{ $product->price }}"
                                                                class="form-control order-input input-price"
                                                                id='prod_price' name='price' step="0.01">
                                                            <select class="form-select select-order select-price"
                                                                aria-placeholder="price"
                                                                aria-label="Default select example" id='prod_valuta'
                                                                name='currency'>
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
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="upload-img">
                                                        <h5 class="product-name order-product">@lang('public.image')</h5>
                                                        <input type="file" value="{{ $product->image }}"
                                                            class="image-input" name="image">
                                                    </div>
                                                    <img src='assets/image/{{ $product->image }}' id='review_image'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">@lang('public.close')</button>
                                        <button type="submit" class="btn btn-primary">@lang('public.add_product')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                <tr>
                    <td colspan="10">
                        <div>{!! $products->appends(Request::all())->links() !!}</div>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="10">There are no data.</td>
                </tr>
            @endif

        </tbody>
    </table>

    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="add-product" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="add">
                            <h5 class="product-name order-product">@lang('public.add_new')</h5>
                            <h6 class="pre-text">@lang('public.provide')</h6>
                            <hr>
                            <div class="row order-info">
                                <div class="col-12 col-lg-6">
                                    @if (Auth::user()->lang_en == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') EN</h5>
                                            <input type="text" name='en_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_ge == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') GE</h5>
                                            <input type="text" name='ge_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_am == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') AM</h5>
                                            <input type="text" name='am_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_az == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') AZ</h5>
                                            <input type="text" name='az_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_de == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') DE</h5>
                                            <input type="text" name='de_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_kz == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') KZ</h5>
                                            <input type="text" name='kz_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_ru == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') RU</h5>
                                            <input type="text" name='ru_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_tj == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') TJ</h5>
                                            <input type="text" name='tj_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_ua == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') UA</h5>
                                            <input type="text" name='ua_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_uz == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') UZ</h5>
                                            <input type="text" name='uz_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif

                                    @if (Auth::user()->lang_tr == 1)
                                        <div>
                                            <h5 class="product-name order-product">@lang('public.product') TR</h5>
                                            <input type="text" name='tr_name'
                                                class="form-control order-input prod-name">
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="product-name order-product">@lang('public.code')</h5>
                                        <input type="text" name='code' class="form-control order-input prod-name">
                                    </div>
                                    <div>
                                        <h5 class="product-name order-product">@lang('public.price')</h5>
                                        <div class="flex price-4">
                                            <input type="number" class="form-control order-input input-price"
                                                id='prod_price' name='price' step="0.01">
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
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="upload-img">
                                        <h5 class="product-name order-product">@lang('public.image')</h5>
                                        <input type="file" class="image-input" name="image">
                                    </div>
                                    <img src='' id='review_image'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('public.close')</button>
                        <button type="submit" class="btn btn-primary">@lang('public.add_product')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <script>
            swal("{{ $errors->first() }}", "", "error");
        </script>
    @endif

@stop
