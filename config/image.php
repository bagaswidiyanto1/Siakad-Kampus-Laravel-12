<?php

use Intervention\Image\Drivers\Gd\Driver;

return [

    'driver' => Driver::class,
    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
        'strip' => false,
    ]
];
