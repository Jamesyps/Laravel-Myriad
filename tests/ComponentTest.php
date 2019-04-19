<?php

namespace Jamesyps\Myriad\Tests;

use Jamesyps\Myriad\Contracts\ComponentInterface;

/**
 * Class ComponentTest
 *
 * @group component
 */
class ComponentTest extends MyriadTestCase
{
    public function testFriendlyName()
    {
        $this->assertEquals('button', $this->getComponent('button.blade.php')->getName());
        $this->assertEquals('table row', $this->getComponent('table-row.blade.php')->getName());
    }

    public function testIdentifyingKey()
    {
        $this->assertEquals(
            'button',
            $this->getComponent('button.blade.php')->getKey()
        );

        $this->assertEquals(
            'nested.quote',
            $this->getComponent('nested/quote.blade.php')->getKey()
        );

        $this->assertEquals(
            'nested.deep-nesting.panel',
            $this->getComponent('nested/deep-nesting/panel.blade.php')->getKey()
        );
    }

    public function testRelativeViewPath()
    {
        $this->assertEquals(
            'components.nested.deep-nesting.panel',
            $this->getComponent('nested/deep-nesting/panel.blade.php')->getViewPath()
        );
    }

    public function testSource()
    {
        $check = '<button class="btn btn-{{ $type ?? \'default\' }}">
    {{ $slot }}
</button>';

        $this->assertEquals($check, $this->getComponent('button.blade.php')->getSource());
    }

    public function testSourceRemovesComments()
    {
        $this->assertNotContains('{{--', $this->getComponent('button.blade.php')->getSource());
    }

    public function testSourceCanIncludeComments()
    {
        $this->assertContains('{{--', $this->getComponent('button.blade.php')->getSource(true));
        $this->assertContains('{{--', $this->getComponent('button.blade.php')->getSourceWithComments());
    }

    public function testSourceErrorsOnInvalidView()
    {
        $this->expectException(\DomainException::class);

        $this->getComponent(md5(rand()))->getSource();
    }

    public function testRootNamespace()
    {
        $this->assertEquals('*', $this->getComponent('button.blade.php')->getNamespace());
    }

    public function testNestedNamespace()
    {
        $this->assertEquals(
            'nested.deep-nesting',
            $this->getComponent('nested/deep-nesting/panel.blade.php')->getNamespace()
        );
    }

    public function testParent()
    {
        $this->assertEquals(
            'deep-nesting',
            $this->getComponent('nested/deep-nesting/panel.blade.php')->getParent()
        );
    }

    public function testDocumentedAttributes()
    {
        $component = $this->getComponent('button.blade.php');

        $attributes = [
            'version' => '1.0.0',
            'status'  => 'draft',
        ];

        $invalidKeys = [
            'slots',
            'variables',
        ];

        $this->assertEquals($attributes, $component->getAttributes());

        foreach ($invalidKeys as $key) {
            $this->assertArrayNotHasKey($key, $component->getAttributes());
        }
    }

    public function testDocumentedSlots()
    {
        $slots = [
            'default' => 'My Button',
        ];

        $this->assertEquals($slots, $this->getComponent('button.blade.php')->getSlots());
    }

    public function testDocumentedVariables()
    {
        $variables = [
            'type' => 'primary',
        ];

        $this->assertEquals($variables, $this->getComponent('button.blade.php')->getVariables());
    }

    public function testDocumentedText()
    {
        $this->assertContains(
            'Lorem ipsum dolor sit amet',
            $this->getComponent('button.blade.php')->getText()
        );
    }

    public function testDocumentedTextDoesNotContainFrontMatter()
    {
        $this->assertNotContains(
            '---', // this is valid markdown, but is not in the test source
            $this->getComponent('button.blade.php')->getText()
        );
    }

    public function testSerializeToArray()
    {
        $data = $this->getComponent('button.blade.php')->toArray();

        $keys = [
            'key',
            'name',
            'source',
            'namespace',
            'parent',
            'attributes',
            'slots',
            'variables',
            'text',
            'view_path',
        ];

        foreach($keys as $key) {
            $this->assertArrayHasKey($key, $data);
        }
    }

    public function testSerializeToJson()
    {
        $json = $this->getComponent('button.blade.php')->toJson();
        $string = (string) $this->getComponent('button.blade.php');

        $this->assertJson($json, 'toJson()');
        $this->assertJson($string, '__toString()');
    }

    public function testMagicGetterReturnsNullOnInvalid()
    {
        $component = $this->getComponent('button.blade.php');

        $this->assertNull($component->non_existent);
    }
}
