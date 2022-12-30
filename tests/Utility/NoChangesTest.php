<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Utility;

use StageRightLabs\Bloom\Primitives\String32;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Utility\NoChanges
 */
class NoChangesTest extends TestCase
{
    /**
     * @test
     * @covers ::__set
     */
    public function no_properties_can_be_set()
    {
        $str = String32::of('Hello World');
        $str->foo = 'bar';

        $this->assertFalse(property_exists($str, 'foo'));
    }

    /**
     * @test
     * @covers ::__unset
     */
    public function no_properties_can_be_removed()
    {
        $str = String32::of('Hello World');
        unset($str->value);

        $this->assertEquals('Hello World', $str->getValue());
    }
}
