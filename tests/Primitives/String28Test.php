<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\String28;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\String28
 */
class String28Test extends TestCase
{
    /**
     * @test
     * @covers ::maxByteLength
     */
    public function it_has_a_maximum_length_of_28_bytes()
    {
        $str = String28::of('Hello World');
        $this->assertEquals(28, $str->maxByteLength());
    }
}
