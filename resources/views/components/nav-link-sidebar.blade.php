@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-3 py-2 text-sm font-bold rounded-lg bg-blue-50 text-blue-700 transition'
            : 'flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>