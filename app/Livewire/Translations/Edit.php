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

class Edit extends Component
{
    use WithPagination;
    use CleanupInput;
    use WireUiActions;

    public $locale = null;

    public $search = '';
    public $sort;
    public $dir;
    public $filter = 'A';

    protected $queryString = ['sort', 'dir'];

    public $rid = null;
    public $key = null;
    public $value = null;
    public $done = null;
    public $info = null;


    public function sortBy($field)
    {
        $field = strtolower($field);
        if ($field === $this->sort) {
            $this->dir = $this->dir == 'asc' ? 'desc' : 'asc';
        } else {
            $this->dir = 'asc';
        }
        $this->sort = in_array($field, ['group', 'key', 'value', 'done']) ? $field : 'key';
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount($locale)
    {
        $this->locale = $locale;
    }
    public function render()
    {
        $this->authorize('view', Translation::class);

        if (!in_array($this->dir, [null, 'asc', 'desc']))
            $this->dir = 'asc';

        $entries = Translation::where('locale', $this->locale);

        $entries = $entries->search(['group', 'key', 'value'], $this->search);

        if ($this->filter == 'O')
            $entries = $entries->where('done', false);
        if ($this->filter == 'D')
            $entries = $entries->where('done', true);


        switch ($this->sort) {
            case null:
                break;
            case 'done':
            case 'cooked':
                $entries = $entries->orderBy($this->sort, $this->dir == 'asc' ? 'desc' : 'asc');
                break;
            default:
                $entries = $entries->orderBy($this->sort, $this->dir);
                break;
        }

        $entries = $entries->orderBy('key', 'asc');

        return view('livewire.translations.edit', ['entries' => $entries->paginate(10)]);
    }

    public function fastEntry(Translation $entry)
    {
        $this->authorize('update', $entry);

        $entry->value = $entry->key;
        $entry->done = true;
        $entry->save();
    }

    public function editEntry(Translation $entry)
    {
        $this->authorize('update', $entry);

        $this->rid = $entry->id;
        $this->key = $entry->key;
        $this->value = $entry->value;
        $this->done = $entry->done;
        $this->info = Translation::where('key', $entry->key)->whereNot('id', $entry->id)->pluck('value', 'locale')
            ->implode(function ($item, $key) {
                return $key . ': ' . $item;
            }, "\n");
    }

    public function saveEntry()
    {

        $this->rid = $this->cleanInput($this->rid);
        $this->value = $this->cleanInput($this->value);
        $this->done = $this->cleanInput($this->done);

        $entry = null;
        if (!is_null($this->rid)) {
            $entry = Translation::where('id', $this->rid)->first();
        }

        if (is_null($entry)) {
            $this->notification()->error('Error', 'Entry not found');
            $this->rid = null;
            return;
        }

        $this->authorize('update', $entry);

        $entry->value = $this->value;
        $entry->done = $this->done;
        $entry->save();

        $this->rid = null;
        $this->key = null;
        $this->value = null;
        $this->done = null;
        $this->info = null;

        $this->notification()->success('Entry saved', 'The translation entry was successfully saved');

    }

    public function deleteEntry(Translation $entry)
    {
        $this->authorize('update', $entry);

        $entry->delete();
    }


}
