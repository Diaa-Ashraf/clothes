@extends('layouts.admin')

@section('title')
 {{ __('stocks_list') }}
@endsection

@section('content')

 <a href="{{ route('stocks.create') }}" class="btn btn-primary mb-3">{{ __('create_stock') }}</a>

 <div class="card">
 <div class="card-body">
 <table class="table table-hover">
 <thead class="thead-dark">
 <tr>
 <th scope="col">#</th>
 <th scope="col">{{ __('stock_name') }}</th>
 <th scope="col">{{ __('stock_code') }}</th>
 <th scope="col">{{ __('stock_price') }}</th>
 <th scope="col">{{ __('stock_quantity') }}</th>
 <th scope="col">{{ __('product') }}</th> <!-- المنتج -->
 <th scope="col">{{ __('actions') }}</th>
 </tr>
 </thead>
 <tbody>
 @foreach ($stocks as $stock)
 <tr>
 <th>{{ $loop->iteration }}</th>
 <td>{{ $stock->name }}</td>
 <td>{{ $stock->code }}</td>
 <td>{{ number_format($stock->price, 2) }}</td>
 <td>{{ $stock->quantity }}</td>
 <td>{{ $stock->product->name ?? '-' }}</td> <!-- اسم المنتج -->
 <td>
 <a href="{{ route('stocks.show', $stock->id) }}" class="btn btn-sm btn-outline-success">{{ __('buttons_show') }}</a>
 <a href="{{ route('stocks.edit', $stock->id) }}" class="btn btn-sm btn-outline-warning">{{ __('buttons_edit') }}</a>
 <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" style="display:inline;">
 @csrf
 @method('DELETE')
 <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('are_you_sure') }}')">{{ __('buttons_delete') }}</button>
 </form>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>

 <!-- روابط الصفحات لو فيه pagination -->
 <div class="mt-3">
 {{ $stocks->links() }}
 </div>
 </div>
 </div>

@endsection
