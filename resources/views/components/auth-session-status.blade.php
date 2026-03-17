@props(['status'])

@if ($status)
<div {{ $attributes->merge(['class' => 'auth-alert']) }}>
    <i class="fas fa-info-circle"></i>
    <span>{{ $status }}</span>
</div>
@endif
