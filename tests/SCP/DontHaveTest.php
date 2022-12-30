<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\SCP\DontHave;
use StageRightLabs\Bloom\SCP\MessageType;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\DontHave
 */
class DontHaveTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $dontHave = (new DontHave())->withReqHash(UInt256::of('1'));
        $buffer = XDR::fresh()->write($dontHave);

        $this->assertEquals('AAAAAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAx', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_request_hash_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new DontHave());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $dontHave = XDR::fromBase64('AAAAAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAx')
            ->read(DontHave::class);

        $this->assertInstanceOf(DontHave::class, $dontHave);
        $this->assertInstanceOf(MessageType::class, $dontHave->getType());
        $this->assertInstanceOf(UInt256::class, $dontHave->getReqHash());
        $this->assertEquals(UInt256::of('1')->getBytes(), $dontHave->getReqHash()->getBytes());
    }

    /**
     * @test
     * @covers ::withType
     * @covers ::getType
     */
    public function it_accepts_a_message_type()
    {
        $dontHave = (new DontHave())->withType(MessageType::dontHave());

        $this->assertInstanceOf(DontHave::class, $dontHave);
        $this->assertInstanceOf(MessageType::class, $dontHave->getType());
    }

    /**
     * @test
     * @covers ::withReqHash
     * @covers ::getReqHash
     */
    public function it_accepts_a_req_hash()
    {
        $dontHave = (new DontHave())->withReqHash(UInt256::of('1'));

        $this->assertInstanceOf(DontHave::class, $dontHave);
        $this->assertInstanceOf(UInt256::class, $dontHave->getReqHash());
    }
}
