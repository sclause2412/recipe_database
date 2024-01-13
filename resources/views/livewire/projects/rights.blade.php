<div class="space-y-2">

    <x-input placeholder="{{ __('Search...') }}" wire:model.live="search" />

    <x-table>
        <x-slot name="header">
            <x-table.head :direction="$sort === 'user' ? $dir : null" sortable wire:click="sortBy('user')">{{ __('User') }}</x-table.head>
            <x-table.head :direction="$sort === 'right' ? $dir : null" sortable wire:click="sortBy('right')">{{ __('Right') }}</x-table.head>
            <x-table.head :direction="$sort === 'level' ? $dir : null" sortable wire:click="sortBy('level')">{{ __('Level') }}</x-table.head>
            @if ($edit)
                <x-table.head />
            @endif
        </x-slot>
        @forelse ($rights as $right)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell><x-user :user="$right->user" /></x-table.cell>
                <x-table.cell>{{ $right->right }}</x-table.cell>
                <x-table.cell>{{ $right->level }}</x-table.cell>
                @if ($edit)
                    <x-table.cell>
                        <div class="flex space-x-2 text-lg">
                            <x-button icon="swap" secondary title="{{ __('Change level') }}"
                                wire:click="changeRight({{ $right->id }})" />
                            <x-deletebutton icon wire:click="deleteRight({{ $right->id }})" />
                        </div>
                    </x-table.cell>
                @endif
            </x-table.row>
        @empty
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell colspan="3">
                    <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                </x-table.cell>
            </x-table.row>
        @endforelse
        @if ($edit)
            <x-slot name="footer">
                <x-table.row wire:loading.class.delay="opacity-50">
                    <x-table.cell>
                        <x-select :async-data="route('search', [
                            'area' => 'project.rights',
                            'model' => 'user',
                            'project' => $project,
                        ])" option-label="name" option-value="id" wire:model="user" />
                    </x-table.cell>
                    <x-table.cell>
                        <x-select :options="RightConstants::array(true, 'id', 'name')" class="text-sm" option-label="name" option-value="id"
                            wire:model="right" />
                    </x-table.cell>
                    <x-table.cell>
                        <x-select :options="LevelConstants::filter([1, 2])->array(true, 'id', 'name')" option-label="name" option-value="id" wire:model="level" />
                    </x-table.cell>
                    <x-table.cell>
                        <x-button secondary wire:click="addRight()">Add</x-button>
                    </x-table.cell>
                </x-table.row>
            </x-slot>
        @endif
    </x-table>
    {{ $rights->links() }}
</div>
