@props(['type' => 'info'])

@php
    $classes = 'p-4 mb-4 text-sm rounded-lg ';
    
    switch ($type) {
        case 'success':
            $classes .= 'text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400';
            break;
        case 'danger':
            $classes .= 'text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400';
            break;
        case 'warning':
            $classes .= 'text-yellow-800 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300';
            break;
        default:
            $classes .= 'text-blue-800 bg-blue-50 dark:bg-gray-800 dark:text-blue-400';
            break;
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} role="alert">
    {{ $slot }}
</div>
