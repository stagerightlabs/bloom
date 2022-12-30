<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerKeyOffer;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerKeyOffer
 */
class LedgerKeyOfferTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerKeyOffer = (new LedgerKeyOffer())
            ->withSellerId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withOfferId(Int64::of(1));
        $buffer = XDR::fresh()->write($ledgerKeyOffer);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAE=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_seller_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerKeyOffer = (new LedgerKeyOffer())
            ->withOfferId(Int64::of(1));
        XDR::fresh()->write($ledgerKeyOffer);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_offer_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerKeyOffer = (new LedgerKeyOffer())
            ->withSellerId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));
        XDR::fresh()->write($ledgerKeyOffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerKeyOffer = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAE=')
            ->read(LedgerKeyOffer::class);

        $this->assertInstanceOf(LedgerKeyOffer::class, $ledgerKeyOffer);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyOffer->getSellerId());
        $this->assertInstanceOf(Int64::class, $ledgerKeyOffer->getOfferId());
    }

    /**
     * @test
     * @covers ::withSellerId
     * @covers ::getSellerId
     */
    public function it_accepts_a_seller_id()
    {
        $ledgerKeyOffer = (new LedgerKeyOffer())
            ->withSellerId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(LedgerKeyOffer::class, $ledgerKeyOffer);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyOffer->getSellerId());
    }

    /**
     * @test
     * @covers ::withSellerId
     * @covers ::getSellerId
     */
    public function it_accepts_a_string_as_a_seller_id()
    {
        $ledgerKeyOffer = (new LedgerKeyOffer())
            ->withSellerId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(LedgerKeyOffer::class, $ledgerKeyOffer);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyOffer->getSellerId());
    }

    /**
     * @test
     * @covers ::withSellerId
     * @covers ::getSellerId
     */
    public function it_accepts_an_addressable_as_a_seller_id()
    {
        $ledgerKeyOffer = (new LedgerKeyOffer())
            ->withSellerId(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(LedgerKeyOffer::class, $ledgerKeyOffer);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyOffer->getSellerId());
    }

    /**
     * @test
     * @covers ::withOfferId
     * @covers ::getOfferId
     */
    public function it_accepts_an_offer_id()
    {
        $ledgerKeyOffer = (new LedgerKeyOffer())
            ->withOfferId(Int64::of(1));

        $this->assertInstanceOf(LedgerKeyOffer::class, $ledgerKeyOffer);
        $this->assertInstanceOf(Int64::class, $ledgerKeyOffer->getOfferId());
    }
}
