<section>
    <div class="w-3/4">
        <div class="mx-3">
            {{ $user->main_role_name }}
            @if ($user->main_role->hasDepartment())
                <strong> ({{ $user->main_department->name }})</strong>
            @endif
        </div>
    </div>
</section>
