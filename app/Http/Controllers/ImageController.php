<?php

namespace App\Http\Controllers;

use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ImageController extends Controller
{

    private static function check_size($size)
    {
        $validsize = config('image.thumbnail.validsize', [100]);

        if (in_array($size, $validsize))
            return $size;

        $nextsmall = min($validsize);
        foreach ($validsize as $vs) {
            if ($vs < $size)
                $nextsmall = max($nextsmall, $vs);
        }

        return $nextsmall;
    }

    private static function response($filename)
    {
        return response()->file($filename);
    }

    private static function create_404($sizex, $sizey)
    {
        if (is_null($sizex))
            $sizex = $sizey;
        if (is_null($sizey))
            $sizey = $sizex;
        $destPath = 'images/404/';
        $destFile = $destPath . $sizex . '_' . $sizey . '.jpg';
        if (Storage::fileExists($destFile)) {
            return Storage::path($destFile);
        } else {
            $imgSource = Image::make(resource_path('images/404.png'));
            $imgSource->resize($sizex, $sizey, function ($constraint) {
            });
            $fp = Storage::path($destFile);
            Storage::createDirectory($destPath);
            $imgSource->save($fp, 100, 'jpg');
            return $fp;
        }
    }

    private static function create_thumb($sizex, $sizey, $path, $dir, $name, $aspect, $fill = false)
    {
        $sourceFile = 'images/upload/' . $path . '.jpg';
        $destFile = 'images/thumbnail/' . $path . '/' . $dir . '/' . $name . '.jpg';
        $sourcePath = substr($sourceFile, 0, strrpos($sourceFile, '/'));
        $destPath = substr($destFile, 0, strrpos($destFile, '/'));
        if (Storage::fileExists($destFile)) {
            return Storage::path($destFile);
        } else {
            if (Storage::fileExists($sourceFile)) {
                $imgSource = Image::make(Storage::path($sourceFile));
                if ($aspect) {
                    if ($fill) {
                        $f = max($sizex / $imgSource->width(), $sizey / $imgSource->height());
                        $imgSource->resize($imgSource->width() * $f, $imgSource->height() * $f, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $imgSource->crop($sizex, $sizey);
                    } else {
                        $imgSource->resize($sizex, $sizey, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                } else {
                    $imgSource->resize($sizex, $sizey, function ($constraint) {
                    });
                }
                $fp = Storage::path($destFile);
                Storage::createDirectory($destPath);
                $imgSource->save($fp, 100, 'jpg');
                return $fp;
            } else {
                return self::create_404($sizex, $sizey);
            }
        }
    }

    private static function get_orig($path)
    {
        $sourceFile = 'images/upload/' . $path . '.jpg';
        if (Storage::fileExists($sourceFile)) {
            return Storage::path($sourceFile);
        } else {
            return self::create_404(100, 100);
        }
    }

    public static function save($img, $path)
    {
        if (self::exists($path))
            self::delete($path);

        $destFile = 'images/upload/' . $path . '.jpg';
        $destPath = substr($destFile, 0, strrpos($destFile, '/'));
        $fp = Storage::path($destFile);
        Storage::createDirectory($destPath);
        $img->save($fp, 100, 'jpg');
        return true;
    }

    public static function upload($file, $path)
    {
        if (self::exists($path))
            self::delete($path);

        $imgSource = Image::make($file);
        $destFile = 'images/upload/' . $path . '.jpg';
        $destPath = substr($destFile, 0, strrpos($destFile, '/'));
        $fp = Storage::path($destFile);
        Storage::createDirectory($destPath);
        $imgSource->save($fp, 100, 'jpg');
        return true;
    }

    public static function exists($path, $dir = null, $size1 = null, $size2 = null)
    {
        $dir = strtolower($dir);

        switch ($dir) {
            case 'w':
                $destFile = 'images/thumbnail/' . $path . '/w/' . $size1 . '.jpg';
                break;
            case 'h':
                $destFile = 'images/thumbnail/' . $path . '/h/' . $size1 . '.jpg';
                break;
            case 'f':
                $destFile = 'images/thumbnail/' . $path . '/f/' . $size1 . '_' . $size2 . '.jpg';
                break;
            case 'c':
                $destFile = 'images/thumbnail/' . $path . '/c/' . $size1 . '_' . $size2 . '.jpg';
                break;
            case 's':
                $destFile = 'images/thumbnail/' . $path . '/s/' . $size1 . '_' . $size2 . '.jpg';
                break;
            default:
                $destFile = 'images/upload/' . $path . '.jpg';
        }
        if (is_null($destFile))
            return false;
        return Storage::exists($destFile);
    }

    public static function delete($path)
    {
        $file = 'images/upload/' . $path . '.jpg';
        if (Storage::delete($file)) {
            $file = 'images/thumbnail/' . $path;
            Storage::deleteDirectory($file);
        }
    }

    private static function _move($oldpath, $newpath)
    {
        Storage::delete($newpath);
        Storage::move($oldpath, $newpath);
    }

    public static function rename($oldpath, $newpath)
    {
        if (!Storage::exists('images/upload/' . $oldpath . '.jpg'))
            return false;

        self::_move('images/upload/' . $oldpath . '.jpg', 'images/upload/' . $newpath . '.jpg');
        self::_move('images/thumbnail/' . $oldpath, 'images/thumbnail/' . $newpath);
        return true;
    }

    public static function orig($path)
    {
        $image =  self::get_orig($path);
        if (is_null($image))
            abort(404);
        return self::response($image);
    }

    public static function width($sizex, $path)
    {
        $sizex = self::check_size($sizex);

        $image =  self::create_thumb($sizex, null, $path, 'w', $sizex, true);
        if (is_null($image))
            abort(404);
        return self::response($image);
    }

    public static function height($sizey, $path)
    {
        $sizey = self::check_size($sizey);

        $image =  self::create_thumb(null, $sizey, $path, 'h', $sizey, true);
        if (is_null($image))
            abort(404);
        return self::response($image);
    }

    public static function fit($sizex, $sizey, $path)
    {
        $sizex = self::check_size($sizex);
        $sizey = self::check_size($sizey);

        $image =  self::create_thumb($sizex, $sizey, $path, 'f', $sizex . '_' . $sizey, true);
        if (is_null($image))
            abort(404);
        return self::response($image);
    }

    public static function fill($sizex, $sizey, $path)
    {
        $sizex = self::check_size($sizex);
        $sizey = self::check_size($sizey);

        $image =  self::create_thumb($sizex, $sizey, $path, 'c', $sizex . '_' . $sizey, true, true);
        if (is_null($image))
            abort(404);
        return self::response($image);
    }

    public static function stretch($sizex, $sizey, $path)
    {
        $sizex = self::check_size($sizex);
        $sizey = self::check_size($sizey);

        $image =  self::create_thumb($sizex, $sizey, $path, 's', $sizex . '_' . $sizey, false);
        if (is_null($image))
            abort(404);
        return self::response($image);
    }
}
