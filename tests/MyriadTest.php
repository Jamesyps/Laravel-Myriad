<?php

namespace Jamesyps\Myriad\Tests;

use Jamesyps\Myriad\Contracts\ComponentRepositoryInterface;
use Jamesyps\Myriad\Facades\Myriad;

class MyriadTest extends MyriadTestCase
{

    public function testStyleAssetsAreLoaded()
    {
        $this->app['config']->set('myriad.preview.css', [
            'css/example.css'
        ]);

        $this->assertIsArray(Myriad::previewStyles());
        $this->assertCount(1, Myriad::previewStyles());
        $this->assertContains('css/example.css', Myriad::previewStyles());
    }

    public function testScriptAssetsAreLoaded()
    {
        $this->app['config']->set('myriad.preview.js', [
            'js/example.js'
        ]);

        $this->assertIsArray(Myriad::previewScripts());
        $this->assertCount(1, Myriad::previewScripts());
        $this->assertContains('js/example.js', Myriad::previewScripts());
    }

    public function testComponentRepositoryIsAccessible()
    {
        $this->assertInstanceOf(ComponentRepositoryInterface::class, Myriad::components());
    }

}
