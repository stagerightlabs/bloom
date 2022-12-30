<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\SendMore;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\SendMore
 */
class SendMoreTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $sendMore = (new SendMore())->withNumMessages(UInt32::of(1));
        $buffer = XDR::fresh()->write($sendMore);

        $this->assertEquals('AAAAAQ==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_message_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new SendMore());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $sendMore = XDR::fromBase64('AAAAAQ==')->read(SendMore::class);

        $this->assertInstanceOf(SendMore::class, $sendMore);
        $this->assertInstanceOf(UInt32::class, $sendMore->getNumMessages());
    }

    /**
     * @test
     * @covers ::withNumMessages
     * @covers ::getNumMessages
     */
    public function it_accepts_a_uint32_as_a_message_count()
    {
        $sendMore = (new SendMore())->withNumMessages(UInt32::of(1));

        $this->assertInstanceOf(SendMore::class, $sendMore);
        $this->assertInstanceOf(UInt32::class, $sendMore->getNumMessages());
    }

    /**
     * @test
     * @covers ::withNumMessages
     * @covers ::getNumMessages
     */
    public function it_accepts_a_native_int_as_a_message_count()
    {
        $sendMore = (new SendMore())->withNumMessages(1);

        $this->assertInstanceOf(SendMore::class, $sendMore);
        $this->assertInstanceOf(UInt32::class, $sendMore->getNumMessages());
    }
}
