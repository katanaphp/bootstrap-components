@props([
    'label',
    'value' => 25,
    'color' => 'primary',
    'min' => 0,
    'max' => 100,
    'striped' => false,
    'animated' => false,
])

<div {{ $attributes->class(['progress'])->merge([
    'role' => 'progressbar',
    'aria-valuenow' => $value,
    'aria-valuemin' => $min,
    'aria-valuemax' => $max,
]) }}

    @if ($label) aria-label="{{ $label }}" @endif>

    <div @class([
        'progress-bar',
        "bg-{$color}" => $slot->isEmpty(),
        "text-bg-{$color}" => !$slot->isEmpty(),
        'progress-bar-striped' => $striped || $animated,
        'progress-bar-animated' => $animated,
    ]) style="width: {{ sprintf('%d', ($value / $max) * 100) }}%">
        {{ $slot }}
    </div>
</div>
