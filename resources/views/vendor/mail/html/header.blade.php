<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<a href="{{ route('dashboard') }}" class="inline-block px-5 -py-2 h-20 mx-auto">
    <x-logo class="h-full w-full"/>
</a>
@endif
</a>
</td>
</tr>
