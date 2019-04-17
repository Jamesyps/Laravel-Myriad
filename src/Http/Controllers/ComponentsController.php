<?php

namespace Jamesyps\Myriad\Http\Controllers;

use Illuminate\Http\Request;
use Jamesyps\Myriad\Contracts\ComponentRepositoryInterface;

class ComponentsController
{
    public function __invoke(ComponentRepositoryInterface $componentRepository, Request $request)
    {
        return view('myriad::index', [
            'namespaces' => $componentRepository->namespaces(),
            'components' => $componentRepository->grouped(
                $request->query('namespace')
            ),
        ]);
    }
}
