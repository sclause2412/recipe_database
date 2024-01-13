<?php

namespace App\Livewire\Translations;

use App\Actions\Livewire\CleanupInput;
use App\Models\Translation;
use Illuminate\Filesystem\Filesystem;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

class Import extends Component
{
    use WithFileUploads;
    use CleanupInput;
    use WireUiActions;

    public $locale = null;
    public $file;
    public $status = 'D';
    public $mode = 'I';


    public function mount($locale)
    {
        $this->locale = $locale;
    }
    public function render()
    {
        $this->authorize('update', Translation::class);

        return view('livewire.translations.import');
    }

    public function run()
    {
        $this->authorize('update', Translation::class);

        $this->status = $this->cleanInput($this->status);
        $this->mode = $this->cleanInput($this->mode);

        $this->validate([
            'status' => ['required', 'in:F,O,D'],
            'mode' => ['required', 'in:I,O,E,U'],
            'file' => ['required'],
        ]);

        // Read file

        $fs = new Filesystem();
        $file = $this->file->path();
        if (!($fs->exists($file) && $fs->isFile($file))) {
            $this->notification()->error('Error', 'File was not uploaded correctly.');
            return;
        }

        $source = $fs->json($file);
        if (is_null($source) || count($source) == 0) {
            $this->notification()->error('Error', 'File does not contain valid JSON data.');
            return;
        }


        // Analyze file
        $data = [];

        if ($this->is_assoc($source)) {
            $first = $source[array_key_first($source)];
            if (is_array($first)) {
                if (isset($first['group']) && isset($first['value'])) {
                    foreach ($source as $key => $line) {
                        $data[$line['group']][$key] = ['value' => $line['value'], 'done' => $line['done'] ?? null];
                    }

                } else {

                    if ($this->is_assoc($first)) {
                        if (isset($first['value'])) {
                            foreach ($source as $key => $line) {
                                if (preg_match('/^([a-z0-9]+)\\.([a-z0-9\.]+)$/', $key, $m2)) {
                                    $group = $m2[1];
                                    $key = $m2[2];
                                } else {
                                    $group = '_json';
                                }
                                $data[$group][$key] = ['value' => $line['value'], 'done' => $line['done'] ?? null];
                            }
                        } else {
                            if (is_array($first[array_key_first($first)])) {
                                foreach ($source as $group => $lines) {
                                    foreach ($lines as $key => $line) {
                                        $data[$group][$key] = ['value' => $line['value'], 'done' => $line['done'] ?? null];
                                    }
                                }
                            } else {
                                foreach ($source as $group => $lines) {
                                    foreach ($lines as $key => $value) {
                                        $data[$group][$key] = ['value' => $value, 'done' => null];
                                    }
                                }
                            }
                        }


                    } else {
                        foreach ($source as $group => $lines) {
                            foreach ($lines as $line) {
                                $data[$group][$line['key']] = ['value' => $line['value'], 'done' => $line['done'] ?? null];
                            }
                        }
                    }
                }
            } else {
                foreach ($source as $key => $value) {
                    if (preg_match('/^([a-z0-9]+)\\.([a-z0-9\.]+)$/', $key, $m2)) {
                        $group = $m2[1];
                        $key = $m2[2];
                    } else {
                        $group = '_json';
                    }
                    $data[$group][$key] = ['value' => $value, 'done' => null];
                }
            }
        } else {
            if (isset($source[0]['group'])) {
                foreach ($source as $line) {
                    $data[$line['group']][$line['key']] = ['value' => $line['value'], 'done' => $line['done'] ?? null];
                }
            } else {
                foreach ($source as $line) {
                    $key = $line['key'];
                    if (preg_match('/^([a-z0-9]+)\\.([a-z0-9\.]+)$/', $key, $m2)) {
                        $group = $m2[1];
                        $key = $m2[2];
                    } else {
                        $group = '_json';
                    }
                    $data[$group][$key] = ['value' => $line['value'], 'done' => $line['done'] ?? null];
                }

            }
        }

        // Update status
        foreach ($data as $group => $keys) {
            foreach ($keys as $key => $value) {
                $done = $value['done'];
                if ($this->status == 'O') {
                    $done = false;
                }
                if ($this->status == 'D') {
                    $done = true;
                }
                if (is_null($value['value'])) {
                    $done = false;
                }

                if (is_null($done)) {
                    $this->notification()->error('Error', 'File does not contain status information. Select other option for status.');
                    $this->validate([
                        'status' => ['required', 'in:O,D'],
                    ]);
                    return;
                }
                $data[$group][$key]['done'] = $done;
            }
        }

        // Write to database

        foreach ($data as $group => $keys) {
            foreach ($keys as $key => $value) {
                $entry = Translation::where('locale', $this->locale)->where('group', $group)->where('key', $key)->first();
                if (is_null($entry)) {
                    if (!($this->mode == 'I' || $this->mode == 'O')) {
                        continue;
                    }
                    $entry = new Translation();
                    $entry->locale = $this->locale;
                    $entry->group = $group;
                    $entry->key = $key;
                }
                if (($this->mode == 'I' || $this->mode == 'E') && !is_null($entry->value)) {
                    continue;
                }
                $entry->value = $value['value'];
                $entry->done = $value['done'];
                $entry->save();
            }
        }

        $this->notification()->success('Import done', 'Import was successfully done');
    }

    private function is_assoc($array)
    {
        $keys = array_keys($array);
        return $keys !== array_keys($keys);
    }

}
