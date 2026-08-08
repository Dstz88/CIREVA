@props([
    'user' => null,
    'size' => 'w-9 h-9',
    'textSize' => 'text-xs',
    'ring' => ''
])

@php
    $u = $user ?? Auth::user();
    $avatar = $u?->avatar;
    $name = $u?->name ?? 'User';
    $initials = strtoupper(substr($name, 0, 2));
@endphp

@if($avatar)
    <img src="{{ Storage::url($avatar) }}" alt="{{ $name }}"
        class="{{ $size }} rounded-full object-cover shadow-sm {{ $ring }}">
@else
    <div
        class="{{ $size }} rounded-full bg-[#0096C7] text-white flex items-center justify-center font-bold {{ $textSize }} uppercase shadow-sm {{ $ring }}">
        {{ $initials }}
    </div>
@endif