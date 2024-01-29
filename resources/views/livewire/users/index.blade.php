    <div class="space-y-2">

        <x-input placeholder="{{ __('Search...') }}" wire:model.live="search" />

        <x-table>
            <x-slot name="header">
                <x-table.head />
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <x-table.head>{{ __('Image') }}</x-table.head>
                @endif
                <x-table.head :direction="$sort === 'name' ? $dir : null" sortable wire:click="sortBy('name')">{{ __('Username') }}</x-table.head>
                <x-table.head :direction="$sort === 'email' ? $dir : null" sortable wire:click="sortBy('email')">{{ __('Email') }}</x-table.head>
                <x-table.head :direction="$sort === 'firstname' ? $dir : null" sortable wire:click="sortBy('firstname')">{{ __('First name') }}
                </x-table.head>
                <x-table.head :direction="$sort === 'lastname' ? $dir : null" sortable wire:click="sortBy('lastname')">{{ __('Last name') }}
                </x-table.head>
                <x-table.head :direction="$sort === 'verified' ? $dir : null" sortable wire:click="sortBy('verified')">{{ __('Verified') }}
                </x-table.head>
                <x-table.head :direction="$sort === 'active' ? $dir : null" sortable wire:click="sortBy('active')">{{ __('Active') }}
                </x-table.head>
                <x-table.head :direction="$sort === '2fa' ? $dir : null" sortable wire:click="sortBy('2fa')">{{ __('2FA') }}</x-table.head>
                <x-table.head :direction="$sort === 'admin' ? $dir : null" sortable wire:click="sortBy('admin')">{{ __('Admin') }}
                </x-table.head>
                <x-table.head />
            </x-slot>
            @forelse ($users as $user)
                <x-table.row wire:loading.class.delay="opacity-50">
                    <x-table.cell>
                        <x-icon class="{{ user_icon_color($user) }} h-6 w-6 rounded-md p-1" name="user" />
                    </x-table.cell>
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <x-table.cell><img alt="" class="mr-2 h-8 w-8 rounded-full object-cover"
                                src="{{ $user->profile_photo_url }}" /></x-table.cell>
                    @endif
                    <x-table.cell>{{ $user->name }}</x-table.cell>
                    <x-table.cell>{{ $user->email }}</x-table.cell>
                    <x-table.cell>{{ $user->firstname }}</x-table.cell>
                    <x-table.cell>{{ $user->lastname }}</x-table.cell>
                    <x-table.cell>{{ is_null($user->email_verified_at) ? __('No') : __('Yes') }}</x-table.cell>
                    <x-table.cell>{{ $user->active ? __('Yes') : __('No') }}</x-table.cell>
                    <x-table.cell>{{ is_null($user->two_factor_confirmed_at) ? __('No') : __('Yes') }}
                    </x-table.cell>
                    <x-table.cell>{{ $user->admin ? __('Yes') : __('No') }}</x-table.cell>
                    <x-table.cell buttons>
                        <x-link button icon="eye" route="users.show,{{ $user->id }}"
                            title="{{ __('Show') }}" />
                        <x-link button icon="pencil" route="users.edit,{{ $user->id }}"
                            title="{{ __('Edit') }}" />
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row wire:loading.class.delay="opacity-50">
                    <x-table.cell colspan="10">
                        <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-table>
        {{ $users->links() }}
    </div>
