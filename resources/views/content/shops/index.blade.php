@extends('layouts/contentNavbarLayout')

@section('title', 'Shops')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Shops</h5>
        <a href="{{ route('shops.create') }}" class="btn btn-primary">Add Shop</a>
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
                    <th>Shop</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($shops as $shop)
                <tr>
                    <td>
                        @include('_partials.table-name-cell', [
                            'name' => $shop->name,
                            'image' => $shop->image,
                            'fallbackIcon' => 'bx-store',
                            'fallbackIconClass' => 'text-success',
                        ])
                    </td>
                    <td>{{ $shop->phone }}</td>
                    <td>{{ $shop->email }}</td>
                    <td>{{ $shop->city }}</td>
                    <td>@include('_partials.table-status-badge', ['status' => $shop->status])</td>
                    <td>
                        @include('_partials.table-actions', [
                            'editUrl' => route('shops.edit', $shop->id),
                            'deleteUrl' => route('shops.destroy', $shop->id),
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No shops found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($shops->hasPages())
        <div class="px-4 pb-4">
            {{ $shops->links() }}
        </div>
    @endif
</div>
@endsection
