<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class GlobalSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!is_elevated()) {
            abort(403);
        }
        return view('globalsettings');
    }


    private static $config = null;

    private static function load()
    {
        if (!is_null(static::$config)) {
            return;
        }

        $config = Storage::json('globalconfig.json');
        if (is_null($config)) {
            $config = [];
        }
        static::$config = $config;
    }

    public static function set($setting, $value)
    {
        static::load();

        static::$config[$setting] = $value;

        $config = static::$config;
        Storage::put('globalconfig.json', json_encode($config, JSON_PRETTY_PRINT));
    }

    public static function get($setting, $default)
    {
        static::load();

        return static::$config[$setting] ?? $default;
    }

}
