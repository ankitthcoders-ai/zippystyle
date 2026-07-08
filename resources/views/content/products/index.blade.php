@extends('layouts/contentNavbarLayout')

@section('title', 'Products')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Products</h5>
        <a href="{{ route('form-layouts-vertical') }}" class="btn btn-primary">Add Product</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success m-4 mb-0">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Gender</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>SKU</th>
                    <th>Shop</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($products as $product)
                <tr>
                    <td>
                        @include('_partials.table-name-cell', [
                            'name' => $product->name,
                            'image' => $product->firstImagePath(),
                            'fallbackIcon' => 'bx-package',
                            'fallbackIconClass' => 'text-primary',
                        ])
                    </td>
                    <td>{{ ucfirst($product->gender ?? '—') }}</td>
                    <td>₹{{ $product->price }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->shop->name ?? '—' }}</td>
                    <td>@include('_partials.table-status-badge', ['status' => $product->status])</td>
                    <td>
                        @include('_partials.table-actions', [
                            'editUrl' => route('products.edit', $product->id),
                            'deleteUrl' => route('products.destroy', $product->id),
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="px-4 pb-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
