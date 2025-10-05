{{-- It's important that this file does NOT have a newline at the end. --}}

@props([
    'external' => false,
    'navigate' => true,
])

<a @if ($external) target="_blank" @endif @if ($navigate) wire:navigate @endif
    {{ $attributes->class([
        'inline underline',
        'underline-offset-4',
        'hover:decoration-[1.15px]',
        'decoration-gray-300',
        'hover:text-blue-600 hover:decoration-blue-400',
        'transition-colors duration-200',
    ]) }}>{{ $slot }}</a>
