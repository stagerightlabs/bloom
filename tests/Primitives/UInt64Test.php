<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\UInt64
 */
class UInt64Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $integer = UInt64::of(43981);
        $buffer = XDR::fresh()->write($integer)->toBase64();

        $this->assertEquals('AAAAAAAAq80=', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $integer = XDR::fromBase64('AAAAAAAAq80=')->read(UInt64::class);

        $this->assertTrue($integer->isEqualTo(43981));
    }
}
