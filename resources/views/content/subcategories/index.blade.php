@extends('layouts/contentNavbarLayout')

@section('title', 'Subcategories')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Subcategories</h5>
        <a href="{{ route('subcategories.create') }}" class="btn btn-primary">Add Subcategory</a>
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
                    <th>Subcategory</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($subCategories as $subCategory)
                <tr>
                    <td>
                        @include('_partials.table-name-cell', [
                            'name' => $subCategory->name,
                            'image' => $subCategory->image,
                            'fallbackIcon' => 'bx-grid-alt',
                            'fallbackIconClass' => 'text-warning',
                        ])
                    </td>
                    <td>{{ $subCategory->category->category_name ?? '—' }}</td>
                    <td>{{ $subCategory->description ?: '—' }}</td>
                    <td>@include('_partials.table-status-badge', ['status' => $subCategory->status])</td>
                    <td>
                        @include('_partials.table-actions', [
                            'editUrl' => route('subcategories.edit', $subCategory->id),
                            'deleteUrl' => route('subcategories.destroy', $subCategory->id),
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No subcategories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subCategories->hasPages())
        <div class="px-4 pb-4">
            {{ $subCategories->links() }}
        </div>
    @endif
</div>
@endsection
