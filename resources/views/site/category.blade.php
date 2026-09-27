@extends('layouts.site')

@section('title')
 {{ $category->name }}
@endsection

@section('content')
 <!-- Breadcrumb -->
 <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
 <div class="container">
 <div class="row">
 <div class="col-lg-12 text-center">
 <div class="breadcrumb__text">
 <h2>{{ $category->name }}</h2>
 <div class="breadcrumb__option">
 <a href="{{ route('site.home') }}">{{ __('Home') }}</a>
 <span>{{ $category->name }}</span>
 </div>
 </div>
 </div>
 </div>
 </div>
 </section>

 <!-- Category Info -->
 <section class="category-details spad py-5">
 <div class="container">
 <div class="row align-items-center">
 <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
 <div class="category__details__pic">
 <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded shadow-sm" style="width: 100%; object-fit: cover;">
 </div>
 </div>
 <div class="col-lg-8 col-md-6">
 <div class="category__details__text">
 <h3 class="mb-3">{{ $category->name }}</h3>
 <p style="line-height: 1.7;">{{ $category->description }}</p>
 </div>
 </div>
 </div>
 </div>
 </section>

 <!-- Products -->
 <section class="products spad py-5">
 <div class="container">
 <h2 class="mb-4">{{ __('products') }}</h2>
 <div class="row">
 @forelse($category->products as $product)
 <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
 <div class="product__item shadow-sm rounded p-2">
 <div class="product__item__pic set-bg" data-setbg="{{ $product->image_url }}" style="height: 250px; background-size: cover; border-radius: 8px;">
 <ul class="product__item__pic__hover">
 <li><a href="#"><i class="fa fa-heart"></i></a></li>
 <li>
 <button type="button" class="btn-add-to-cart" onclick="addToCart({{ $product->id }})" title="{{ __('Add_to_Cart') }}">
 <i class="fa fa-shopping-cart"></i>
 </button>
 </li>
 </ul>
 </div>
 <div class="product__item__text text-center mt-3">
 <h6><a href="{{ route('product.details', $product->id) }}">{{ $product->name }}</a></h6>
 <h5>${{ number_format($product->price, 2) }}</h5>
 </div>
 </div>
 </div>
 @empty
 <div class="col-12">
 <p class="text-center">{{ __('No products found in this category.') }}</p>
 </div>
 @endforelse
 </div>
 </div>
 </section>
@endsection

@section('js')
 <script>
 // Add to cart function with AJAX
 function addToCart(productId) {
 $.ajax({
 url: '{{ route("AddToCart", ":id") }}'.replace(':id', productId),
 type: 'GET',
 success: function(response) {
 // Check if response is a redirect (product added successfully)
 if (response.redirect) {
 toastr.success('{{ __("Product_added_to_cart_successfully") }}');
 updateCartCount();
 } else {
 // If it's a full page redirect, show success message
 toastr.success('{{ __("Product_added_to_cart_successfully") }}');
 updateCartCount();
 }
 },
 error: function(xhr) {
 let errorMessage = '{{ __("Error_adding_product_to_cart") }}';
 if (xhr.responseJSON && xhr.responseJSON.message) {
 errorMessage = xhr.responseJSON.message;
 } else if (xhr.status === 404) {
 errorMessage = '{{ __("Product_not_found") }}';
 } else if (xhr.status === 419) {
 errorMessage = '{{ __("CSRF_token_mismatch") }}';
 }
 toastr.error(errorMessage);
 }
 });
 }

 // Update cart count
 function updateCartCount() {
 $.get('{{ route("cart.count") }}', function(data) {
 $('.cart-count').text(data.count);
 });
 }
 </script>

 <style>
 .btn-add-to-cart {
 background: none;
 border: none;
 color: white;
 font-size: 16px;
 padding: 8px;
 border-radius: 50%;
 transition: all 0.3s ease;
 cursor: pointer;
 }

 .btn-add-to-cart:hover {
 background: var(--primary-color);
 transform: scale(1.1);
 }

 .btn-add-to-cart:active {
 transform: scale(0.95);
 }
 </style>
@endsection
