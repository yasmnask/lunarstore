@props(['component' => null])
@php
    $type = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];
@endphp
@if ($component && $component->showFlash)
    <div class="alert {{ $type[$component->flashType] }} alert-dismissible fade show" role="alert">
        {{ $component->flashMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
