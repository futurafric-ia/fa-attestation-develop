@props(['route' => '', 'active' => '', 'text' => '', 'hide' => false, 'icon' => false, 'permission' => false])

@if ($permission)
    @if($logged_in_user->hasPermissionTo($permission))
        @if (!$hide)
        <li>
            <a
                href="{{ $route ? route($route) : '#' }}"
                {{ $attributes->merge(["class" => "mb-1 px-2 py-2 flex text-gray-300 items-center font-medium hover:text-3xl hover:text-white group $active"]) }}
            >
                @if($icon) {{ $icon }} @endif
                <span class="ml-4">{{ $slot }}</span>
            </a>
        </li>
        @endif
    @endif
@else
    @if (!$hide)
        <li>
            <a
                href="{{ $route ? route($route) : '#' }}"
                {{ $attributes->merge(["class" => "mb-1 px-2 py-2  text-gray-300 flex items-center font-medium hover:text-3xl hover:text-white group $active"]) }}
            >
                @if($icon) {{ $icon }} @endif
                <span class="ml-4" >{{ $slot }}</span>
            </a>
        </li>
    @endif
@endif
