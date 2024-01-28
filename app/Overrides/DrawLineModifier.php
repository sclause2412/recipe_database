<?php

declare(strict_types=1);

namespace App\Overrides;

use Intervention\Image\Drivers\AbstractDrawModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Geometry\Line;

/**
 * @method ColorInterface backgroundColor()
 * @property Line $drawable
 */
class DrawLineModifier extends AbstractDrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imageantialias($frame->native(), true);
            $this->imagelinethick(
                $frame->native(),
                $this->drawable->start()->x(),
                $this->drawable->start()->y(),
                $this->drawable->end()->x(),
                $this->drawable->end()->y(),
                $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                    $this->backgroundColor()
                ),
                $this->drawable->width()
            );
        }

        return $image;
    }

    private function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
    {
        // GD library does not support thick lines if they are not orthogonal
        if ($thick <= 0) {
            return true;
        }

        if ($thick == 1) {
            return imageline($image, $x1, $y1, $x2, $y2, $color);
        }

        imagefilledellipse($image, $x1, $y1, $thick, $thick, $color);
        imagefilledellipse($image, $x2, $y2, $thick, $thick, $color);

        if ($x1 == $x2) {
            return imagefilledrectangle(
                $image,
                intval(min($x1, $x2) - floor($thick / 2)),
                intval(min($y1, $y2)),
                intval(max($x1, $x2) + floor($thick / 2)),
                intval(max($y1, $y2)),
                $color
            );
        }

        if ($y1 == $y2) {
            return imagefilledrectangle(
                $image,
                intval(min($x1, $x2)),
                intval(min($y1, $y2) - floor($thick / 2)),
                intval(max($x1, $x2)),
                intval(max($y1, $y2) + floor($thick / 2)),
                $color
            );
        }



        $k = ($y2 - $y1) / ($x2 - $x1);

        $x = $thick / 2 * $k / sqrt(pow($k, 2) + 1);
        $y = $x / $k;

        $points = [
            intval(floor($x1 + $x)),
            intval(floor($y1 - $y)),
            intval(floor($x2 + $x)),
            intval(floor($y2 - $y)),
            intval(floor($x2 - $x)),
            intval(floor($y2 + $y)),
            intval(floor($x1 - $x)),
            intval(floor($y1 + $y)),
        ];

        imagefilledpolygon($image, $points, 4, $color);
        return imagepolygon($image, $points, 4, $color);
    }
}
