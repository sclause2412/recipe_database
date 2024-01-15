<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Symfony\Component\Finder\Finder;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Translation::class);

        return view('translations.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale)
    {
        $this->authorize('view', Translation::class);

        return view('translations.edit', ['locale' => $locale]);
    }

    public function export(string $locale)
    {
        $this->authorize('view', Translation::class);

        return view('translations.export', ['locale' => $locale]);
    }


    public function import(string $locale)
    {
        $this->authorize('update', Translation::class);

        return view('translations.import', ['locale' => $locale]);
    }


    public function sync(string $locale)
    {
        $this->authorize('update', Translation::class);

        $translations = Translation::whereNot('locale', $locale)->whereNotIn(
            'key',
            Translation::where('locale', $locale)->pluck('key')->toArray()
        )->orderBy('key')->orderBy('done', 'desc')->groupBy(['group', 'key'])->select(['group', 'key'])->get();
        foreach ($translations as $entry) {
            $trans = new Translation();
            $trans->locale = $locale;
            $trans->group = $entry->group == '-' ? '_json' : $entry->group;
            $trans->key = $entry->key;
            $trans->save();
        }

        return redirect()->route('translations.edit', $locale);
    }


    private function _readfiles(): array
    {
        $alldata = [];
        $langPath = realpath(App::langPath());
        if ($langPath === false) {
            return $alldata;
        }

        foreach (Finder::create()->files()->in($langPath) as $file) {
            $filename = $file->getRealPath();
            $name = $file->getFilenameWithoutExtension();
            $ext = $file->getExtension();
            $path = explode(DIRECTORY_SEPARATOR, $file->getRelativePath());
            if (strtolower($ext) == 'json') {
                if ($path[0] != '') { // no support for json files in subdirectories
                    continue;
                }
                $group = '_json';
                $locale = $name;
                $data = json_decode($file->getContents(), true);
                foreach ($data as $key => $value) {
                    $alldata[$locale][$group][$key] = $value;
                }
            }
            if (strtolower($ext) == 'php') {
                if ($path[0] == '') { // no support for php files not in locale directory
                    continue;
                }
                $locale = $path[0];
                if (count($path) > 1) {
                    array_shift($path);
                    $group = implode('.', $path) . '.' . $name;
                } else {
                    $group = $name;
                }

                $fs = new Filesystem();
                $data = $fs->getRequire($filename);

                $data = Arr::dot($data);

                foreach ($data as $key => $value) {
                    $alldata[$locale][$group][$key] = $value;
                }
            }

        }

        return $alldata;
    }


    public function read()
    {
        $this->authorize('update', Translation::class);


        // Step 1: Read from existing translation files
        $data = $this->_readfiles();

        $allkeys = [];

        foreach ($data as $groups) {
            foreach ($groups as $keys) {
                $allkeys = array_merge($allkeys, array_keys($keys));
            }
        }

        $allkeys = array_merge($allkeys, Translation::groupBy('key')->pluck('key')->toArray());

        $allkeys = array_values(array_unique($allkeys));


        // Step 2: Read from all project files containing __ translations
        $locale = App::getLocale();

        foreach (Finder::create()->files()->name('*.php')->contains('/[^\\w]__\\(.+?\\)/')->exclude(['vendor', 'node_modules', 'tests', 'storage'])->in(App::basePath()) as $file) {
            $content = $file->getContents();

            if (preg_match_all('/[^\\w]__\\((.+?)\\)/', $content, $matches)) {

                foreach ($matches[1] as $key) {
                    $quote = substr($key, 0, 1);
                    if (preg_match('/' . $quote . '((?:\\\\' . $quote . '|.)+?)' . $quote . '\\s*(.?)/', $key, $m)) {
                        if ($m[2] == '' || $m[2] == ',') {
                            $key = $m[1];

                            $key = str_replace('\\' . $quote, $quote, $key);

                            if (!in_array($key, $allkeys)) {
                                if (preg_match('/^([a-z0-9]+)\\.([A-Za-z0-9\.]+)$/', $key, $m2)) {
                                    $group = $m2[1];
                                    $key = $m2[2];
                                    if (!in_array($key, $allkeys)) {
                                        array_push($allkeys, $key);
                                    }
                                } else {
                                    $group = '_json';
                                    array_push($allkeys, $key);
                                }
                                if (!isset($data[$locale][$group][$key])) {
                                    $data[$locale][$group][$key] = null;
                                }
                            }
                        }
                    }

                }
            }
        }

        // Step 3: Scan vendor files for existing translation files to take over

        $fs = new Filesystem();

        $locales = array_unique(array_merge(array_keys($data), Translation::groupBy('locale')->select('locale')->pluck('locale')->toArray()));
        foreach ($locales as $loc) {
            foreach (Finder::create()->files()->name('*.php')->path('lang/' . $loc)->in(App::basePath() . '/vendor') as $file) {
                $content = Arr::dot($fs->getRequire($file->getRealPath()));
                $group = $file->getFilenameWithoutExtension();
                foreach ($content as $key => $value) {
                    if (is_string($value)) {
                        if (!isset($data[$loc][$group][$key]) || is_null($data[$loc][$group][$key])) {
                            $data[$loc][$group][$key] = $value;
                        }
                    }
                }
            }
        }

        // Step 4: Write results to database
        foreach ($data as $locale => $groups) {
            foreach ($groups as $group => $keys) {
                foreach ($keys as $key => $value) {

                    $translation = Translation::where('locale', $locale)->where('key', $key)->first();
                    if (is_null($translation)) {
                        $translation = new Translation();
                        $translation->locale = $locale;
                        $translation->key = $key;
                    }
                    $translation->group = $group;
                    $translation->value = $value;
                    $translation->done = !is_null($value);
                    $translation->save();

                }
            }
        }

        return redirect()->route('translations.index');
    }

    public function write()
    {
        $this->authorize('update', Translation::class);

        $langPath = realpath(App::langPath());

        $fs = new Filesystem();
        $ds = DIRECTORY_SEPARATOR;
        $lf = PHP_EOL;

        if ($langPath === false) {
            $p1 = App::basePath();
            $p2 = App::langPath();

            if (strlen($p2) > strlen($p1)) {
                if (substr($p2, 0, strlen($p1)) == $p1) {
                    $langPath = realpath(App::basePath());
                    if ($langPath !== false) {
                        $langPath .= substr($p2, strlen($p1));
                        $fs->ensureDirectoryExists($langPath);
                    }
                }
            }

            $langPath = realpath(App::langPath());

            if ($langPath === false) {
                return redirect()->route('translations.index')->with('message', ['title' => __('Error'), 'text' => __('The path for language files was not found'), 'icon' => 'error']);
            }
        }

        foreach (Finder::create()->files()->in($langPath) as $file) {
            $fs->delete($file->getRealPath());
        }

        $data = [];
        $translations = Translation::where('done', true)->whereNot('group', '-')->get();
        foreach ($translations as $entry) {
            $data[$entry->locale][$entry->group][$entry->key] = $entry->value ?? '';
        }

        foreach ($data as $locale => $groups) {
            foreach ($groups as $group => $keys) {
                if ($group == '_json') {
                    $path = $langPath . $ds . $locale . '.json';
                    $content = json_encode($keys, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
                } else {
                    $path = $langPath . $ds . $locale . $ds . str_replace('.', $ds, $group) . '.php';
                    $content = '<?php' . $lf . $lf .
                        '// This file was automatically generated. Please use /translation link to modify it.' . $lf . $lf .
                        'return ' . var_export(Arr::undot($keys), true) . ';' . $lf;
                }

                $fs->ensureDirectoryExists($fs->dirname($path));
                $fs->put($path, $content);
            }
        }

        session(['available_locales' => null]);

        return redirect()->route('translations.index');
    }

    public function mode()
    {
        $this->authorize('view', Translation::class);

        session(['translate_mode' => !session('translate_mode', false)]);

        return redirect()->route('translations.index');
    }


    public static function available_locales()
    {
        $available_locales = session('available_locales', null);
        if (is_null($available_locales)) {
            $available_locales = [];

            $langPath = realpath(App::langPath());

            if ($langPath !== false) {
                foreach (Finder::create()->files()->depth(0)->name('*.json')->in($langPath) as $file) {
                    array_push($available_locales, $file->getFilenameWithoutExtension());
                }
            }

            natsort($available_locales);
            session(['available_locales' => $available_locales]);
        }

        return $available_locales;

    }

    public static function add_missing($key, $locale)
    {
        if (preg_match('/^([a-z0-9]+)\\.([A-Za-z0-9\.]+)$/', $key, $m2)) {
            $group = $m2[1];
            $key = $m2[2];
        } else {
            $group = '_json';
        }

        if (!Translation::where('key', $key)->where('locale', $locale)->exists()) {
            $entry = new Translation();
            $entry->locale = $locale;
            $entry->group = $group;
            $entry->key = $key;
            $entry->save();
        }

        if (session('translate_mode', false)) {
            return '[[[' . $key . ']]]';
        } else {
            return $key;
        }
    }
}
