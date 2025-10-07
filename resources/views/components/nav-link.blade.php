@props(['href', 'icon', 'label'])

@php
    $active = Request::is(trim($href, '/'));
@endphp

<a href="{{ $href }}" 
   class="flex items-center gap-3 px-3 py-2 rounded-md transition-all duration-200
   {{ $active ? 'bg-primary text-white' : 'hover:bg-navItem hover:text-white text-accent' }}">
   <i class="{{ $icon }} text-base"></i>
   <span>{{ $label }}</span>
</a>
