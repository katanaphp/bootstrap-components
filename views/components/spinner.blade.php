@props(['variant' => 'border', 'small' => false, 'role' => 'status', 'color' => 'primary', 'label' => 'Loading...'])

<span @if($role) role="{{ $role }}" @endif {{ $attributes->class([
    "spinner-{$variant}",
    "text-{$color}",
    "spinner-{$variant}-sm" => $small,
]) }}>
    @if($label) <span class="visually-hidden">{{ $label }}</span> @endif
</span>