@props(['status'])

@php
    $classes = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ';
    
    switch ($status) {
        case 'approved':
        case 'verified':
        case 'success':
            $classes .= 'bg-green-100 text-green-800';
            break;
        case 'pending':
        case 'warning':
            $classes .= 'bg-yellow-100 text-yellow-800';
            break;
        case 'rejected':
        case 'danger':
            $classes .= 'bg-red-100 text-red-800';
            break;
        case 'published':
        case 'info':
            $classes .= 'bg-blue-100 text-blue-800';
            break;
        default:
            $classes .= 'bg-gray-100 text-gray-800';
            break;
    }
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
