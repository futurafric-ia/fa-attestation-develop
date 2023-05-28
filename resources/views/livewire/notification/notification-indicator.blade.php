<a
    @click="dropdownOpen = !dropdownOpen"
    class="relative block rounded-full border-2 border-transparent text-gray-400  hover:text-gray-600 hover:bg-gray-200 focus:outline-none focus:text-white focus:bg-teal-800 transition duration-150 ease-in-out"
>
    <svg class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>
    <span
        class="{{ $hasNotification ? 'absolute top-0 right-0 text-white bg-red-600 text-xs font-semibold rounded-full h-4 w-4' : 'hidden' }}">
        <livewire:notification-count/>
    </span>
</a>
