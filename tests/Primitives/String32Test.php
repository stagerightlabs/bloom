<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\String32;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\String32
 */
class String32Test extends TestCase
{
    /**
     * @test
     * @covers ::maxByteLength
     */
    public function it_has_a_maximum_length_of_32_bytes()
    {
        $str = String32::of('Hello World');
        $this->assertEquals(32, $str->maxByteLength());
    }
}
