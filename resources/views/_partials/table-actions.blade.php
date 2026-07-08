@props([
    'editUrl' => null,
    'deleteUrl' => null,
    'deleteConfirm' => 'Are you sure?',
])

<div class="dropdown">
    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="icon-base bx bx-dots-vertical-rounded"></i>
    </button>
    <div class="dropdown-menu">
        @if($editUrl)
            <a class="dropdown-item" href="{{ $editUrl }}">
                <i class="icon-base bx bx-edit-alt me-1"></i> Edit
            </a>
        @endif
        @if($deleteUrl)
            <form action="{{ $deleteUrl }}" method="POST" onsubmit="return confirm('{{ $deleteConfirm }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item text-danger">
                    <i class="icon-base bx bx-trash me-1"></i> Delete
                </button>
            </form>
        @endif
    </div>
</div>
