<div class="modal fade " id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
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
                            <br>
                            <div class="dropdown">
                                <button class="btn new-product-btn dropdown-toggle" onclick="myFunction()" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Select product
                                </button>
                                <div id="myDropdown" class="dropdown-content">
                                    <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">

                                    @foreach ($products as $product)
                                    <li class="option save-li" id="product_{{ $product->id }}" value="{{ $product->id }}">
                                        {{ $product->en_name }}
                                    </li>
                                    <script>
                                        $("#product_{{ $product->id }}").click(function() {
                                            var id = $("#product_{{ $product->id }}").val();
                                            $.ajax({
                                                type: 'get',
                                                url: '{{ url("/addProductsAjax")}}',
                                                data: 'id=' + id,
                                                success: function(response) {
                                                    console.log(response)
                                                    $('#prod_ge_name').val(response[0].ge_name);
                                                    $('#prod_en_name').val(response[0].en_name);
                                                    $('#prod_am_name').val(response[0].am_name);
                                                    $('#prod_az_name').val(response[0].az_name);
                                                    $('#prod_de_name').val(response[0].de_name);
                                                    $('#prod_kz_name').val(response[0].kz_name);
                                                    $('#prod_ru_name').val(response[0].ru_name);
                                                    $('#prod_ua_name').val(response[0].ua_name);
                                                    $('#prod_uz_name').val(response[0].uz_name);
                                                    $('#prod_tj_name').val(response[0].tj_name);
                                                    $('#prod_tr_name').val(response[0].tr_name);
                                                    $('#prod_price').val(response[0].price);
                                                    $('#saved_image').val(response[0].image);
                                                    $("#review_image").last().attr("src", '../assets/image/' + response[0].image);
                                                    $("#review_image").addClass("review-image");
                                                    $("#myDropdown").removeClass("show");
                                                }
                                            })
                                        })
                                    </script>
                                    @endforeach

                                </div>
                            </div>
                            <br>
                            @if(Auth::user()->lang_en == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') EN</h5>
                                <input type="text" id='prod_en_name' name='ge_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_ge == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') GE</h5>
                                <input type="text" id='prod_ge_name' name='en_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_am == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') AM</h5>
                                <input type="text" id='prod_am_name' name='am_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_az == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') AZ</h5>
                                <input type="text" id='prod_az_name' name='az_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_de == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') DE</h5>
                                <input type="text" id='prod_de_name' name='de_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_kz == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') KZ</h5>
                                <input type="text" id='prod_kz_name' name='kz_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_ru == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') RU</h5>
                                <input type="text" id='prod_ru_name' name='ru_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_ua == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') UA</h5>
                                <input type="text" id='prod_ua_name' name='ua_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_uz == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') UZ</h5>
                                <input type="text" id='prod_uz_name' name='uz_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_tr == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') TR</h5>
                                <input type="text" id='prod_tr_name' name='tr_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            @if(Auth::user()->lang_tj == 1)
                            <div>
                                <h5 class="product-name order-product">@lang('public.product') TJ</h5>
                                <input type="text" id='prod_tj_name' name='tj_name' class="form-control order-input prod-name">
                            </div>
                            @endif
                            <div>
                                <h5 class="product-name order-product">@lang('public.quantity')</h5>
                                <div class="flex price-4">
                                    <input type="number" class="form-control order-input input-price" id='prod_amount' value="1" name='prod_amount'>
                                </div>
                            </div>
                            {{-- <div>
                                <h5 class="product-name order-product">@lang('public.price')</h5>
                                <div class="flex price-4">
                                    <input type="number" class="form-control order-input input-price" id='prod_price' name='prod_price'>
                                </div>
                            </div> --}}
                            <input type="number" class="form-control order-input input-price" id='prod_price' placeholder="price" name='prod_price'>

                            <input type="number" class="form-control order-input input-price" id='prod_discount_price' placeholder="discount price" name='discount_price'>
                            <select class="form-select select-order select-price" aria-placeholder="price" aria-label="Default select example" id='prod_valuta' name='prod_valuta'>
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
                        <div class="col-12 col-lg-6">
                            <div class="">
                                <h5 class="product-name order-product">@lang('public.save_future')</h5>
                                <select class="form-select select-order" aria-label="Default select example" id='prod_save' name='prod_save'>
                                    <option value="0" selected>@lang('public.no')</option>
                                    <option value='1'>@lang('public.yes')</option>
                                </select>
                            </div>
                            <div class="upload-img">
                                <h5 class="product-name order-product">@lang('public.image')</h5>
                                <input type="file" id="image" class="image-input" name="image[]">
                            </div>
                            <div>
                                <input type="hidden" name="saved_image" value="product.png" id="saved_image">
                            </div>
                            <img src='' id='review_image'>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('public.close')</button>
                <button type="submit" id="reset" class="btn btn-primary">@lang('public.add_product')</button>
            </div>
        </div>
    </div>
</div>