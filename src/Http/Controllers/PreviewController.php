<?php

namespace Jamesyps\Myriad\Http\Controllers;

use Illuminate\Http\Request;
use Jamesyps\Myriad\Contracts\ComponentRepositoryInterface;

class PreviewController
{
    public function __invoke(ComponentRepositoryInterface $componentRepository, Request $request)
    {
        $key = $request->query('key');

        abort_if(is_null($key) || empty($key), 404);

        return view('myriad::preview', $componentRepository->findByKey($key)->toArray());
    }
}
