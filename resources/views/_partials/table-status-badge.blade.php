@props(['status'])

@if($status === 'active')
    <span class="badge bg-label-primary me-1">Active</span>
@else
    <span class="badge bg-label-danger me-1">Deactive</span>
@endif
