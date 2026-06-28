@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

<div class="dropdown">
    <div data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer">
        {{ $trigger }}
    </div>

    <div class="dropdown-menu {{ $align === 'left' ? '' : 'dropdown-menu-end' }}"
         style="min-width:{{ $width === '48' ? '12rem' : $width . 'px' }}">
        {{ $content }}
    </div>
</div>