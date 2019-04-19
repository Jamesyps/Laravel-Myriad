<?php

namespace Jamesyps\Myriad;

use Jamesyps\Myriad\Contracts\ComponentRepositoryInterface;

class Myriad
{

    public static function components(): ComponentRepositoryInterface
    {
        return app()->make(ComponentRepositoryInterface::class);
    }

    public static function previewStyles(): array
    {
        return config('myriad.preview.css', []);
    }

    public static function previewScripts(): array
    {
        return config('myriad.preview.js', []);
    }

}
