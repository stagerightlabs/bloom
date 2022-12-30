<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\InflationPayout;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\InflationPayout
 */
class InflationPayoutTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $inflationPayout = (new InflationPayout())
            ->withDestination('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')
            ->withAmount('1');
        $buffer = XDR::fresh()->write($inflationPayout);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAACYloA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_destination_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $inflationPayout = (new InflationPayout())
            ->withAmount('1');
        XDR::fresh()->write($inflationPayout);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_amount_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $inflationPayout = (new InflationPayout())
            ->withDestination('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        XDR::fresh()->write($inflationPayout);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $inflationPayout = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAACYloA=')
            ->read(InflationPayout::class);

        $this->assertInstanceOf(InflationPayout::class, $inflationPayout);
        $this->assertInstanceOf(AccountId::class, $inflationPayout->getDestination());
        $this->assertInstanceOf(Int64::class, $inflationPayout->getAmount());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_a_string_as_a_destination()
    {
        $inflationPayout = (new InflationPayout())
            ->withDestination('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(InflationPayout::class, $inflationPayout);
        $this->assertInstanceOf(AccountId::class, $inflationPayout->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_addressable_as_a_destination()
    {
        $inflationPayout = (new InflationPayout())
            ->withDestination(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(InflationPayout::class, $inflationPayout);
        $this->assertInstanceOf(AccountId::class, $inflationPayout->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_account_id_as_a_destination()
    {
        $inflationPayout = (new InflationPayout())
            ->withDestination(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(InflationPayout::class, $inflationPayout);
        $this->assertInstanceOf(AccountId::class, $inflationPayout->getDestination());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_amount()
    {
        $inflationPayout = (new InflationPayout())
            ->withAmount('1');

        $this->assertInstanceOf(InflationPayout::class, $inflationPayout);
        $this->assertInstanceOf(Int64::class, $inflationPayout->getAmount());
        $this->assertEquals('1.0000000', $inflationPayout->getAmount()->scale()->toNativeString());
    }
}
