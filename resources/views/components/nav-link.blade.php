@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center w-full px-3 py-2 text-sm font-medium text-black bg-gray-100 rounded-lg'
            : 'inline-flex items-center w-full px-3 py-2 text-sm font-medium text-black hover:bg-gray-50 rounded-lg transition-colors duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
