<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\String64
 */
class String64Test extends TestCase
{
    /**
     * @test
     * @covers ::maxByteLength
     */
    public function it_has_a_maximum_length_of_64_bytes()
    {
        $str = String64::of('Hello World');
        $this->assertEquals(64, $str->maxByteLength());
    }
}
