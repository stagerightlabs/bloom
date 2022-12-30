<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\Liabilities;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\Liabilities
 */
class LiabilitiesTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $liabilities = (new Liabilities())
            ->withBuying(Int64::of(1))
            ->withSelling(Int64::of(2));
        $buffer = XDR::fresh()->write($liabilities);

        $this->assertEquals('AAAAAAAAAAEAAAAAAAAAAg==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_buying_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liabilities = (new Liabilities())
            ->withSelling(Int64::of(2));
        XDR::fresh()->write($liabilities);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_selling_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liabilities = (new Liabilities())
            ->withBuying(Int64::of(1));
        XDR::fresh()->write($liabilities);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $liabilities = XDR::fromBase64('AAAAAAAAAAEAAAAAAAAAAg==')->read(Liabilities::class);

        $this->assertInstanceOf(Liabilities::class, $liabilities);
        $this->assertInstanceOf(Int64::class, $liabilities->getBuying());
        $this->assertTrue($liabilities->getBuying()->isEqualTo(1));
        $this->assertInstanceOf(Int64::class, $liabilities->getSelling());
        $this->assertTrue($liabilities->getSelling()->isEqualTo(2));
    }

    /**
     * @test
     * @covers ::withBuying
     * @covers ::getBuying
     */
    public function it_accepts_an_int64_buying_value()
    {
        $liabilities = (new Liabilities())->withBuying(Int64::of(1));
        $this->assertInstanceOf(Int64::class, $liabilities->getBuying());
    }

    /**
     * @test
     * @covers ::withBuying
     * @covers ::getBuying
     */
    public function it_accepts_a_scaled_amount_buying_value()
    {
        $liabilities = (new Liabilities())->withBuying(ScaledAmount::of(1));
        $this->assertInstanceOf(Int64::class, $liabilities->getBuying());
        $this->assertEquals('10000000', $liabilities->getBuying()->toNativeString());
    }

    /**
     * @test
     * @covers ::withSelling
     * @covers ::getSelling
     */
    public function it_accepts_an_int64_selling_value()
    {
        $liabilities = (new Liabilities())->withSelling(Int64::of(2));
        $this->assertInstanceOf(Int64::class, $liabilities->getSelling());
    }

    /**
     * @test
     * @covers ::withSelling
     * @covers ::getSelling
     */
    public function it_accepts_a_scaled_amount_selling_value()
    {
        $liabilities = (new Liabilities())->withSelling(ScaledAmount::of(2));
        $this->assertInstanceOf(Int64::class, $liabilities->getSelling());
        $this->assertEquals('20000000', $liabilities->getSelling()->toNativeString());
    }
}
