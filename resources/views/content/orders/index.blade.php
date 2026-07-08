@extends('layouts/contentNavbarLayout')

@section('title', 'Orders')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ auth()->user()->isAdmin() ? 'All Orders' : 'My Sales Orders' }}</h5>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Payment</th>
                    <th>Amount</th>
                    <th>Products</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? '—' }}</td>
                    <td>{{ $order->paymentMethod->name ?? '—' }}</td>
                    <td>₹{{ number_format($order->total_paid_price, 2) }}</td>
                    <td>{{ $order->purchasedProducts->count() }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="px-4 pb-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
