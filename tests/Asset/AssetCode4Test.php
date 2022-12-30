<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Asset;

use StageRightLabs\Bloom\Asset\AssetCode4;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\AssetCode4
 */
class AssetCode4Test extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_from_a_string()
    {
        $assetCode4 = AssetCode4::of('ABCD');

        $this->assertInstanceOf(AssetCode4::class, $assetCode4);
        $this->assertEquals('ABCD', $assetCode4->getCode());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $assetCode4 = AssetCode4::of('ABCD');
        $buffer = XDR::fresh()->write($assetCode4);

        $this->assertEquals('QUJDRA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $assetCode4 = XDR::fromBase64('QUJDRA==')->read(AssetCode4::class);

        $this->assertInstanceOf(AssetCode4::class, $assetCode4);
        $this->assertEquals('ABCD', $assetCode4->getCode());
    }

    /**
     * @test
     * @covers ::withCode
     * @covers ::getCode
     */
    public function it_accepts_a_code()
    {
        $assetCode4 = (new AssetCode4())->withCode('ABCD');

        $this->assertInstanceOf(AssetCode4::class, $assetCode4);
        $this->assertEquals('ABCD', $assetCode4->getCode());
    }

    /**
     * @test
     * @covers ::withCode
     */
    public function it_will_not_accept_codes_longer_than_four_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new AssetCode4())->withCode(str_repeat('A', 5));
    }

    /**
     * @test
     * @covers ::toNativeString
     * @covers ::__toString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $assetCode4 = AssetCode4::of('ABCD');
        $this->assertEquals('ABCD', $assetCode4->toNativeString());
    }

    /**
     * @test
     * @covers ::toNativeString
     * @covers ::__toString
     */
    public function it_returns_an_empty_string_if_no_issuer_is_set()
    {
        $this->assertEmpty((new AssetCode4())->toNativeString());
    }
}
