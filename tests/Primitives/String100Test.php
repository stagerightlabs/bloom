<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\String100;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\String100
 */
class String100Test extends TestCase
{
    /**
     * @test
     * @covers ::maxByteLength
     */
    public function it_has_a_maximum_length_of_100_bytes()
    {
        $str = String100::of('Hello World');
        $this->assertEquals(100, $str->maxByteLength());
    }
}
