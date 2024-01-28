<?php

namespace App\Actions\Files;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;

trait HasImage
{
    /**
     * Upload image.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @param  string     $path     The path for the image file.
     *                              Specify only the part which comes in the URL without extension
     *                              Example: images/example
     *                              final path will be https://example.com/images/example.png
     * @param  array|null $config
     * @return string|bool
     */
    public function uploadImage(UploadedFile $image, string $path, ?array $config = null): string|bool
    {
        $config = array_merge([
            'width' => config('image.image.width', 1000),
            'height' => config('image.image.height', 1000),
        ], $config ?? []);


        $img = (new ImageManager(config('image.driver')))->read($image->path());
        $img->cover($config['width'], $config['height']);

        $imgData = $img->toPng();

        $destFile = $path . '.png';

        if (Storage::put('public/' . $destFile, $imgData, ['visibility' => 'public', 'directory_visibility' => 'public'])) {
            return Storage::url($destFile);
        }

        return false;
    }

    /**
     * Delete image.
     *
     * @param  string  $path
     * @return void
     */
    public function deleteImage(string $path)
    {
        $destFile = $path . '.png';

        if (Storage::fileExists('public/' . $destFile)) {
            Storage::delete('public/' . $destFile);
        }
    }

    /**
     * Get image public URL if available or false if not existing.
     *
     * @param  string     $path     The path for the image file.
     *                              Specify only the part which comes in the URL without extension
     *                              Example: images/example
     *                              final path will be https://example.com/images/example.png
     * @return string|bool
     */
    public function getImage(?string $path): string|bool
    {
        if (is_null($path) || $path == '') {
            return false;
        }

        $destFile = $path . '.png';

        if (Storage::fileExists('public/' . $destFile)) {
            return Storage::url('public/' . $destFile);
        }

        return false;
    }

    /**
     * Copy image
     *
     * @param  string     $soure    The path of the source image file.
     *                              Specify only the part which comes in the URL without extension
     *                              Example: images/example
     *                              final path will be https://example.com/images/example.png
     * @param  string     $target   The target path.
     * @return bool
     */
    public function copyImage(string $source, string $target): bool
    {
        $sourceFile = $source . '.png';
        $destFile = $target . '.png';

        if (Storage::fileExists('public/' . $sourceFile)) {
            return Storage::copy('public/' . $sourceFile, 'public/' . $destFile);
        }

        return false;
    }

    /**
     * @param  string     $path     The path for the image file.
     *                              Specify only the part which comes in the URL without extension
     *                              Example: images/example
     *                              final path will be https://example.com/images/example.png
     * @param  array|null $config
     * @return string|bool
     */
    protected function createImage(string $path, ?array $config = null)
    {
        $w = config('image.image.width', 1000);
        $h = config('image.image.height', 1000);
        $config = array_merge([
            'width' => $w,
            'height' => $h,
            'background' => RGBtoHEX(HSVtoRGB(random_float(0, 360), random_float(10, 20), 100)),
            'content' => [
                [
                    'type' => 'line',
                    'color' => RGBtoHEX(HSVtoRGB(random_float(0, 360), 100, random_float(50, 100))),
                    'width' => intval(ceil($w * 0.01)),
                    'from' => [0, 0],
                    'to' => [$w, $h]
                ],
                [
                    'type' => 'line',
                    'width' => intval(ceil($w * 0.01)),
                    'from' => [$w, 0],
                    'to' => [0, $h]
                ],
            ]
        ], $config ?? []);

        $destFile = $path . '.png';

        $img = (new ImageManager(config('image.driver')))->create($config['width'], $config['height'])->fill($config['background']);

        $color1 = '#000000';
        $color2 = '#000000';

        foreach ($config['content'] as $draw) {
            $color1 = $draw['color'] ?? $color1;
            $color1 = $draw['color1'] ?? $color1;
            $color2 = $draw['border']['color'] ?? $color2;
            $color2 = $draw['color2'] ?? $color2;
            switch ($draw['type']) {
                case 'pixel':
                    $img->drawPixel($draw['coords'][0] ?? 0, $draw['coords'][1] ?? 0, $color1);
                    break;
                case 'line':
                    $img->drawLine(function ($line) use ($draw, $color1) {
                        $line->from($draw['coords'][0] ?? 0, $draw['coords'][1] ?? 0);
                        $line->to($draw['coords'][2] ?? 0, $draw['coords'][3] ?? 0);
                        $line->color($color1);
                        $line->width($draw['width'] ?? 1);
                    });
                    break;
                case 'rectangle':
                    $img->drawRectangle(min($draw['coords'][0] ?? 0, $draw['coords'][2] ?? 0), min($draw['coords'][1] ?? 0, $draw['coords'][3] ?? 0), function ($rect) use ($draw, $color1, $color2) {
                        $rect->size(abs($draw['coords'][2] ?? 0 - $draw['coords'][0] ?? 0), abs($draw['coords'][3] ?? 0 - $draw['coords'][1] ?? 0));
                        $rect->background($color1);
                        if (isset($draw['border'])) {
                            $rect->border($color2, $draw['border']['width'] ?? 1);
                        }
                    });
                    break;
                case 'circle':
                    $img->drawCircle($draw['coords'][0] ?? 0, $draw['coords'][1] ?? 0, function ($circle) use ($draw, $color1, $color2) {
                        $circle->radius($draw['radius'] ?? 1);
                        $circle->background($color1);
                        if (isset($draw['border'])) {
                            $circle->border($color2, $draw['border']['width'] ?? 1);
                        }
                    });
                    break;
                case 'ellipse':
                    $img->drawEllipse($draw['coords'][0] ?? 0, $draw['coords'][1] ?? 0, function ($circle) use ($draw, $color1, $color2) {
                        $circle->size($draw['size'][0] ?? 1, $draw['size'][1] ?? 1);
                        $circle->background($color1);
                        if (isset($draw['border'])) {
                            $circle->border($color2, $draw['border']['width'] ?? 1);
                        }
                    });
                    break;
                case 'polygon':
                    $img->drawPolygon(function ($poly) use ($draw, $color1, $color2) {
                        $coords = $draw['coords'] ?? [];
                        for ($i = 0; $i < count($coords); $i += 2) {
                            $poly->point($coords[$i] ?? 0, $coords[$i + 1] ?? 0);
                        }
                        $poly->background($color1);
                        if (isset($draw['border'])) {
                            $poly->border($color2, $draw['border']['width'] ?? 1);
                        }
                    });
                    break;
                case 'text':
                    $img->text($draw['text'] ?? '', $draw['coords'][0] ?? 0, $draw['coords'][1] ?? 0, function ($font) use ($draw, $color1) {
                        $font->filename(resource_path('fonts/Gontserrat-Regular.ttf'));
                        $font->size($draw['size'] ?? 16);
                        $font->align('center');
                        $font->valign('middle');
                        $font->color($color1);
                    });
            }
        }

        $imgData = $img->toPng();

        if (Storage::put('public/' . $destFile, $imgData, ['visibility' => 'public', 'directory_visibility' => 'public'])) {
            return Storage::url($destFile);
        }

        return false;
    }
}
