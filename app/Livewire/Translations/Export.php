<?php

namespace App\Livewire\Translations;

use App\Actions\Livewire\CleanupInput;
use App\Models\Recipe;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Export extends Component
{
    use CleanupInput;
    use WireUiActions;

    public $locale = null;
    public $status = 'D';
    public $done = 'N';
    public $key = 'Y';
    public $simple = 'Y';
    public $type = 'G';


    public function mount($locale)
    {
        $this->locale = $locale;
    }
    public function render()
    {
        $this->authorize('view', Translation::class);

        return view('livewire.translations.export');
    }

    public function run()
    {
        $this->authorize('view', Translation::class);

        $this->status = $this->cleanInput($this->status);
        $this->done = $this->cleanInput($this->done);
        $this->key = $this->cleanInput($this->key);
        $this->simple = $this->cleanInput($this->simple);
        $this->type = $this->cleanInput($this->type);

        // simple only if key = Y, done = N and type = G or D

        $this->validate([
            'status' => ['required', 'in:A,O,D'],
            'done' => ['required', 'in:Y,N'],
            'key' => ['required', 'in:Y,N'],
            'simple' => ['required', ($this->key == 'Y' && $this->done == 'N' && ($this->type == 'G' || $this->type == 'D')) ? 'in:Y,N' : 'in:N'],
            'type' => ['required', 'in:G,L,D'],
        ]);

        $translations = Translation::where('locale', $this->locale);
        if ($this->status == 'O')
            $translations = $translations->where('done', false);
        if ($this->status == 'D')
            $translations = $translations->where('done', true);

        $translations = $translations->orderBy('group')->orderBy('key')->get();

        $data = [];
        foreach ($translations as $entry) {
            switch ($this->type) {
                case 'G':
                    if ($this->key == 'Y') {
                        if ($this->done == 'Y')
                            $data[$entry->group][$entry->key] = ['value' => $entry->value, 'done' => $entry->done];
                        else {
                            if ($this->simple == 'Y')
                                $data[$entry->group][$entry->key] = $entry->value;
                            else
                                $data[$entry->group][$entry->key] = ['value' => $entry->value];
                        }
                    } else {
                        if ($this->done == 'Y')
                            $data[$entry->group][] = ['key' => $entry->key, 'value' => $entry->value, 'done' => $entry->done];
                        else
                            $data[$entry->group][] = ['key' => $entry->key, 'value' => $entry->value];
                    }
                    break;
                case 'L':
                    if ($this->key == 'Y') {
                        if ($this->done == 'Y')
                            $data[$entry->key] = ['group' => $entry->group, 'value' => $entry->value, 'done' => $entry->done];
                        else
                            $data[$entry->key] = ['group' => $entry->group, 'value' => $entry->value];
                    } else {
                        if ($this->done == 'Y')
                            $data[] = ['group' => $entry->group, 'key' => $entry->key, 'value' => $entry->value, 'done' => $entry->done];
                        else
                            $data[] = ['group' => $entry->group, 'key' => $entry->key, 'value' => $entry->value];
                    }
                    break;
                case 'D':
                    if ($entry->group == '_json')
                        $key = $entry->key;
                    else
                        $key = $entry->group . '.' . $entry->key;
                    if ($this->key == 'Y') {
                        if ($this->done == 'Y')
                            $data[$key] = ['value' => $entry->value, 'done' => $entry->done];
                        else {
                            if ($this->simple == 'Y')
                                $data[$key] = $entry->value;
                            else
                                $data[$key] = ['value' => $entry->value];
                        }
                    } else {
                        if ($this->done == 'Y')
                            $data[] = ['key' => $key, 'value' => $entry->value, 'done' => $entry->done];
                        else
                            $data[] = ['key' => $key, 'value' => $entry->value];
                    }
                    break;
            }
        }

        $data = json_encode($data, JSON_PRETTY_PRINT);

        $this->notification()->success('Export done', 'Export was successfully done');

        return response()->streamDownload(function () use ($data) {
            print($data);
        }, 'translate_' . $this->locale . '.json');
    }

}
