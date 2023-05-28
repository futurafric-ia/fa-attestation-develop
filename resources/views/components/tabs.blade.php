@props(['navs'])

<div
    class="w-full"
    x-data="{
        navs: JSON.parse('{{ json_encode($navs) }}'),
        openTab: window.location.hash ? window.location.hash.substring(1) : '{{ $navs[0] }}'
    }"
>
    <ul class="flex">
        <template x-for="(item, index) in navs" :key="index">
            <li class="mr-1 flex flex-grow" :class="index == 0 ? '-mb-px ' : ''">
                <a
                    @click.prevent="openTab = item; window.location.hash = item"
                    :class="openTab === item ? 'border-b border-blue-500 font-medium text-primary-500' : 'text-gray-500 hover:text-primary-800'"
                    class="flex-1 bg-white inline-block py-2 px-4 font-semibold uppercase text-xs"
                    href="#"
                    x-text="item"
                ></a>
            </li>
        </template>
    </ul>
    <div>
        {{ $slot }}
    </div>
</div>
