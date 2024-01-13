<?php

namespace App\Livewire\Translations;

use App\Models\Recipe;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $this->authorize('view', Translation::class);


        $locales = Translation::groupBy('locale')
            ->select('locale')
            ->selectRaw('count(*) as _count')
            ->selectRaw('sum(done) as _done')
            ->orderBy('locale');

        $locales = $locales->paginate(10);

        return view('livewire.translations.index', ['locales' => $locales]);
    }
}
