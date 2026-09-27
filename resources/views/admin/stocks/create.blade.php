@extends('layouts.admin')

@section('title')
 {{ __('create_stock') }}
@endsection

@section('content')

 <div class="card">
 <div class="card-header">
 <h4>{{ __('create_stock') }}</h4>
 </div>
 <div class="card-body">
 <form action="{{ route('stocks.store') }}" method="POST">
 @csrf

 <!-- المنتج المرتبط -->
<div class="form-group mb-3">
 <label for="product_id">{{ __('product') }}</label>

 <select name="product_id" id="product_id" class="form-control select2" required>
 <option value="">{{ __('choose_product') }}</option>
 @foreach($products as $product)
 <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
 {{ $product->name }}
 </option>
 @endforeach
 </select>

 @error('product_id')
 <small class="text-danger">{{ $message }}</small>
 @enderror
</div>


 <!-- الاسم -->
 <div class="form-group mb-3">
 <label for="name">{{ __('stock_name') }}</label>
 <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
 @error('name')
 <small class="text-danger">{{ $message }}</small>
 @enderror
 </div>

 <!-- الكود -->
 <div class="form-group mb-3">
 <label for="code">{{ __('stock_code') }}</label>
 <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required>
 @error('code')
 <small class="text-danger">{{ $message }}</small>
 @enderror
 </div>

 <!-- السعر -->
 <div class="form-group mb-3">
 <label for="price">{{ __('stock_price') }}</label>
 <input type="number" name="price" id="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
 @error('price')
 <small class="text-danger">{{ $message }}</small>
 @enderror
 </div>

 <!-- الكمية -->
 <div class="form-group mb-3">
 <label for="quantity">{{ __('stock_quantity') }}</label>
 <input type="number" name="quantity" id="quantity" class="form-control" step="0.01" value="{{ old('quantity', 0) }}">
 @error('quantity')
 <small class="text-danger">{{ $message }}</small>
 @enderror
 </div>

 <!-- زر الحفظ -->
 <button type="submit" class="btn btn-success">{{ __('buttons_save') }}</button>
 <a href="{{ route('stocks.index') }}" class="btn btn-secondary">{{ __('buttons_back') }}</a>
 </form>
 </div>
 </div>

@endsection

<script>
 $(document).ready(function () {
 $('#product_id').select2({
 placeholder: "{{ __('choose_product') }}",
 allowClear: true,
 width: '100%'
 });
 });
</script>

