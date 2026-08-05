@props(['href' => '#', 'label' => ''])

<a {{ $attributes->class(['alert-link'])->merge([
    'href' => $href
]) }}>
    {{ $label ? $label : $slot }}
</a>