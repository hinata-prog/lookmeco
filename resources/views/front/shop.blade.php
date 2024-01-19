@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Shop</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-6 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 sidebar">
                <div class="sub-title">
                    <h2>Categories</h2>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionExample">

                            @if ($categories->isNotEmpty())

                            @foreach ($categories as $key => $category)
                            <div class="accordion-item">
                                @if($category->subCategories->isNotEmpty())

                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $key }}" aria-expanded="false" aria-controls="collapseOne-{{ $key }}">
                                        {{ $category->name }}
                                    </button>
                                </h2>
                                @else
                                <a href="{{ route('front.shop',$category->slug) }}" class="nav-item nav-link {{ ($categorySelected == $category->id) ? 'text-primary' : '' }}">{{ $category->name }}</a>
                                @endif
                                @if($category->subCategories->isNotEmpty())
                                <div id="collapseOne-{{ $key }}" class="accordion-collapse collapse {{ ($categorySelected == $category->id) ? 'show' : '' }}" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <div class="navbar-nav">
                                            @foreach ($category->subCategories as $subCategory)
                                            <a href="{{ route('front.shop',[$category->slug,$subCategory->slug]) }}" class="nav-item nav-link {{ ($subCategorySelected == $subCategory->id) ? 'text-primary' : '' }}">{{ $subCategory->name }}</a>
                                            @endforeach
                                            <a href="{{ route('front.shop',$category->slug,'others') }}" class="nav-item nav-link {{ ($subCategorySelected == 'other') ? 'text-primary' : '' }}">Others</a>
                                             <a href="{{ route('front.shop',$category->slug) }}" class="nav-item nav-link {{ ($subCategorySelected == 'all') ? 'text-primary' : '' }}">All</a>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                            @endforeach

                            @endif

                        </div>
                    </div>
                </div>

               

                <div class="sub-title mt-5">
                    <h2>Price</h3>
                </div>

                <div class="card">
                    <div class="card-body p-5">
                        <input type="text" class="js-range-slider" name="my_range" value="" />
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <div class="ml-2">
                                <select name="sort" id="sort" class="form-control">
                                    <option value="latest" {{ ($sort == 'latest') ? 'selected' : '' }}>Latest</option>
                                    <option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : '' }}>Price High</option>
                                    <option value="price_asc" {{ ($sort == 'price_asc') ? 'selected' : '' }}>Price Low</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    @if ($products->count() > 0)

                    @foreach ($products as $product)
                    <div class="col-md-4">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="{{ route('front.product',$product->slug) }}" class="product-img">
                                    @if (!empty($product->images) && count($product->images) > 0)
                                    <img src="{{ asset('uploads/products/small/' . $product->images->last()->image) }}" class="card-img-top">
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" class="card-img-top">
                                    @endif
                                </a>
                                <a onclick="addToWishList('{{ $product->id }}')" href="javascript:void(0)" class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                <div class="product-action">
                                    @if($product->track_qty == 'Yes')
                                        @if ($product->qty > 0)
                                            <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart('{{ $product->id }}')">
                                                <i class="fa fa-shopping-cart"></i> &nbsp;ADD TO CART
                                            </a>
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0)" >
                                                <i class="fa fa-shopping-cart"></i> OUT OF STOCK
                                            </a>
                                        @endif
                                    @else
                                        <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart('{{ $product->id }}')">
                                            <i class="fa fa-shopping-cart"></i> &nbsp;ADD TO CART
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="product.php">{{ $product->title }} - {{ $product->measurement_value }} {{ $product->measurement_unit }}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>NRs {{ $product->price }}</strong></span>
                                    @if ($product->compare_price)
                                    <span class="h6 text-underline"><del>NRs {{ $product->compare_price }}</del></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @endif



                    <div class="col-md-12 pt-5">
                       {{ $products->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')

<script>

    rangeSlider = $(".js-range-slider").ionRangeSlider({
        type:"double",
        min:0,
        max:2000,
        from: {{ $priceMin }},
        step: 10,
        to: {{ $priceMax }},
        skin: "round",
        max_postfix: "+",
        prefix: "Rs",
        onFinish: function(){
            apply_filters()
        }
    })

    //saving its instance to var
    var slider = $(".js-range-slider").data("ionRangeSlider");


    $("#sort").change(function(){
        apply_filters();
    })

    function apply_filters(){
        var brands = [];

        $(".brand-label").each(function(){
            if ($(this).is(":checked") == true){
                brands.push($(this).val());
            }
        })
        var url = '{{ url()->current() }}?';

        //price range filter
        url += '&price_min='+slider.result.from+'&price_max='+slider.result.to;


        var keyword = $("#search").val();

        if (keyword.length > 0){
            url += '&search=' + $('#search').val();
        }

        //sorting filter
        url += '&sort=' + $('#sort').val();

        window.location.href = url;

    }


</script>

@endsection
