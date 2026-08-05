@props([
    'variant' => 'primary',
    'dismissible' => false,
])

<div {{ $attributes->class([
    'alert',
    "alert-{$variant}",
    'alert-dismissible' => $dismissible
])->merge([
            'role' => 'alert',
        ]) }}>
    {{ $slot }}

    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>