@props(['active','hover_color'=>null])

@php
$hover_color = $hover_color?$hover_color:'gray';
$classes = ($hover_color ?? false)
            ? "rounded-md p-1 text-sm text-gray-100 bg-$hover_color-500 hover:bg-$hover_color-700 focus:outline-none"
            : "rounded-md p-1 text-sm text-gray-100 bg-$hover_color-500 hover:bg-$hover_color-700 focus:outline-none";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }} 
</a>
             