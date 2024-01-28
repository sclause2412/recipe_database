<?php

use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

return [

    'driver' => (extension_loaded('imagick') && class_exists('Imagick')) ? ImagickDriver::class : GdDriver::class,


    'profile' => [
        'size' => 200,
        'path' => 'profile-photos',
    ],

    'image' => [
        'width' => 1000,
        'height' => 1000,
    ],
];
