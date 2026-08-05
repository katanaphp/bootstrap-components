@props(['level' => 3, 'heading' => ''])

@php
    $tag = sprintf("h%d", $level);
@endphp

<{{ $tag }} {{ $attributes->class(["alert-heading"]) }}>
    {{ $heading ? $heading : $slot }}
</{{ $tag }}>