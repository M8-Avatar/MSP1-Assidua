@props(['messages'])

@if ($messages)
    <ul class="mt-1 mb-0 ps-0" style="list-style:none">
        @foreach ((array) $messages as $message)
            <li style="font-size:.8125rem;color:#E53935;display:flex;align-items:center;gap:5px;margin-top:4px">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#E53935" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif