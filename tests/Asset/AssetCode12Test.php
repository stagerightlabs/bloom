<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Asset;

use StageRightLabs\Bloom\Asset\AssetCode12;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\AssetCode12
 */
class AssetCode12Test extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_from_a_string()
    {
        $assetCode4 = AssetCode12::of('ABCDEFGHIJKL');

        $this->assertInstanceOf(AssetCode12::class, $assetCode4);
        $this->assertEquals('ABCDEFGHIJKL', $assetCode4->getCode());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $assetCode4 = AssetCode12::of('ABCDEFGHIJKL');
        $buffer = XDR::fresh()->write($assetCode4);

        $this->assertEquals('QUJDREVGR0hJSktM', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $assetCode4 = XDR::fromBase64('QUJDREVGR0hJSktM')->read(AssetCode12::class);

        $this->assertInstanceOf(AssetCode12::class, $assetCode4);
        $this->assertEquals('ABCDEFGHIJKL', $assetCode4->getCode());
    }

    /**
     * @test
     * @covers ::withCode
     * @covers ::getCode
     */
    public function it_accepts_a_code()
    {
        $assetCode4 = (new AssetCode12())->withCode('ABCDEFGHIJKL');

        $this->assertInstanceOf(AssetCode12::class, $assetCode4);
        $this->assertEquals('ABCDEFGHIJKL', $assetCode4->getCode());
    }

    /**
     * @test
     * @covers ::withCode
     */
    public function it_will_not_accept_codes_longer_than_twelve_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new AssetCode12())->withCode(str_repeat('A', 13));
    }

    /**
     * @test
     * @covers ::toNativeString
     * @covers ::__toString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $assetCode4 = AssetCode12::of('ABCDEFGHIJKL');
        $this->assertEquals('ABCDEFGHIJKL', $assetCode4->toNativeString());
    }

    /**
     * @test
     * @covers ::toNativeString
     * @covers ::__toString
     */
    public function it_returns_an_empty_string_if_no_issuer_is_set()
    {
        $this->assertEmpty((new AssetCode12())->toNativeString());
    }
}
