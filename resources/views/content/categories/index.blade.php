@extends('layouts/contentNavbarLayout')

@section('title', 'Categories')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Categories</h5>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">Add Category</a>
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
                    <th>Category</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($categories as $category)
                <tr>
                    <td>
                        @include('_partials.table-name-cell', [
                            'name' => $category->category_name,
                            'image' => $category->image,
                            'fallbackIcon' => 'bx-category',
                            'fallbackIconClass' => 'text-info',
                        ])
                    </td>
                    <td>{{ $category->description ?: '—' }}</td>
                    <td>
                        @include('_partials.table-actions', [
                            'editUrl' => route('categories.edit', $category->id),
                            'deleteUrl' => route('categories.destroy', $category->id),
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4 text-muted">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="px-4 pb-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
