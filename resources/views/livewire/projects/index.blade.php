    <div class="space-y-2">

        <x-input placeholder="{{ __('Search...') }}" wire:model.live="search" />

        <x-table>
            <x-slot name="header">
                <x-table.head :direction="$sort === 'name' ? $dir : null" sortable wire:click="sortBy('name')">{{ __('Name') }}</x-table.head>
                <x-table.head :direction="$sort === 'short' ? $dir : null" sortable wire:click="sortBy('short')">{{ __('Short code') }}
                </x-table.head>
                <x-table.head :direction="$sort === 'description' ? $dir : null" sortable wire:click="sortBy('description')">{{ __('Description') }}
                </x-table.head>
                <x-table.head :direction="$sort === 'active' ? $dir : null" sortable wire:click="sortBy('active')">{{ __('Active') }}
                </x-table.head>
                <x-table.head />
            </x-slot>
            @forelse ($projects as $project)
                <x-table.row wire:loading.class.delay="opacity-50">
                    <x-table.cell class="align-top">{{ $project->name }}</x-table.cell>
                    <x-table.cell class="align-top">{{ $project->short }}</x-table.cell>
                    <x-table.cell class="align-top">{!! text_format($project->description) !!}</x-table.cell>
                    <x-table.cell class="align-top">{{ $project->active ? __('Yes') : __('No') }}</x-table.cell>
                    <x-table.cell>
                        <div class="flex space-x-2 text-lg">
                            @can('view', $project)
                                <x-link button icon="eye" route="projects.show,{{ $project->id }}"
                                    title="{{ __('Show') }}" />
                            @endcan
                            @can('update', $project)
                                <x-link button icon="pencil" route="projects.edit,{{ $project->id }}"
                                    title="{{ __('Edit') }}" />
                            @endcan
                        </div>
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
        {{ $projects->links() }}
    </div>
