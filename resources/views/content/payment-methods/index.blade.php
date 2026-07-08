@extends('layouts/contentNavbarLayout')

@section('title', 'Payment Methods')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Payment Methods</h5>
        <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">Add Payment Method</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success m-4 mb-0">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger m-4 mb-0">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($paymentMethods as $paymentMethod)
                <tr>
                    <td>
                        <i class="icon-base bx bx-credit-card icon-md text-primary me-3"></i>
                        <span>{{ $paymentMethod->name }}</span>
                    </td>
                    <td><span class="badge bg-label-info me-1">{{ str_replace('_', ' ', ucfirst($paymentMethod->type)) }}</span></td>
                    <td>{{ $paymentMethod->description ?: '—' }}</td>
                    <td>@include('_partials.table-status-badge', ['status' => $paymentMethod->status])</td>
                    <td>
                        @include('_partials.table-actions', [
                            'editUrl' => route('payment-methods.edit', $paymentMethod->id),
                            'deleteUrl' => route('payment-methods.destroy', $paymentMethod->id),
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No payment methods found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($paymentMethods->hasPages())
        <div class="px-4 pb-4">
            {{ $paymentMethods->links() }}
        </div>
    @endif
</div>
@endsection
