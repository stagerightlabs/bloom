<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\OfferEntry;
use StageRightLabs\Bloom\Ledger\OfferEntryExt;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\OfferEntry
 */
class OfferEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withOfferId(Int64::of(1))
            ->withSellingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withBuyingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withAmount(Int64::of(1000000))
            ->withPrice(Price::of(1, 2));
        $buffer = XDR::fresh()->write($offerEntry);

        $this->assertEquals(
            'AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAAFBQkNEAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpSAAAAAAAPQkAAAAABAAAAAgAAAAAAAAAA',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_seller_account_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $offerEntry = (new OfferEntry())
            ->withOfferId(Int64::of(1))
            ->withSellingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withBuyingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withAmount(Int64::of(1000000))
            ->withPrice(Price::of(1, 2));
        XDR::fresh()->write($offerEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_offer_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSellingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withBuyingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withAmount(Int64::of(1000000))
            ->withPrice(Price::of(1, 2));
        XDR::fresh()->write($offerEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_selling_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withOfferId(Int64::of(1))
            ->withBuyingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withAmount(Int64::of(1000000))
            ->withPrice(Price::of(1, 2));
        XDR::fresh()->write($offerEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_buying_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withOfferId(Int64::of(1))
            ->withSellingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withAmount(Int64::of(1000000))
            ->withPrice(Price::of(1, 2));
        XDR::fresh()->write($offerEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_amount_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withOfferId(Int64::of(1))
            ->withSellingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withBuyingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withPrice(Price::of(1, 2));
        XDR::fresh()->write($offerEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_price_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withOfferId(Int64::of(1))
            ->withSellingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withBuyingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withAmount(Int64::of(1000000));
        XDR::fresh()->write($offerEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $offerEntry = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAAFBQkNEAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpSAAAAAAAPQkAAAAABAAAAAgAAAAAAAAAA')
            ->read(OfferEntry::class);

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(AccountId::class, $offerEntry->getSellerAccountId());
        $this->assertInstanceOf(Int64::class, $offerEntry->getOfferId());
        $this->assertInstanceOf(Asset::class, $offerEntry->getSellingAsset());
        $this->assertInstanceOf(Asset::class, $offerEntry->getBuyingAsset());
        $this->assertInstanceOf(Int64::class, $offerEntry->getAmount());
        $this->assertInstanceOf(Price::class, $offerEntry->getPrice());
        $this->assertInstanceOf(UInt32::class, $offerEntry->getFlags());
        $this->assertInstanceOf(OfferEntryExt::class, $offerEntry->getExtension());
    }

    /**
     * @test
     * @covers ::withSellerAccountId
     * @covers ::getSellerAccountId
     */
    public function it_accepts_an_account_id_as_a_seller_account_id()
    {
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(AccountId::class, $offerEntry->getSellerAccountId());
    }

    /**
     * @test
     * @covers ::withSellerAccountId
     * @covers ::getSellerAccountId
     */
    public function it_accepts_a_string_as_a_seller_account_id()
    {
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(AccountId::class, $offerEntry->getSellerAccountId());
    }

    /**
     * @test
     * @covers ::withSellerAccountId
     * @covers ::getSellerAccountId
     */
    public function it_accepts_an_addressable_as_a_seller_account_id()
    {
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(AccountId::class, $offerEntry->getSellerAccountId());
    }

    /**
     * @test
     * @covers ::withOfferId
     * @covers ::getOfferId
     */
    public function it_accepts_an_offer_id()
    {
        $offerEntry = (new OfferEntry())->withOfferId(Int64::of(1));

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(Int64::class, $offerEntry->getOfferId());
    }

    /**
     * @test
     * @covers ::withSellingAsset
     * @covers ::getSellingAsset
     */
    public function it_accepts_a_selling_asset()
    {
        $offerEntry = (new OfferEntry())
            ->withSellingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')));

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(Asset::class, $offerEntry->getSellingAsset());
    }

    /**
     * @test
     * @covers ::withBuyingAsset
     * @covers ::getBuyingAsset
     */
    public function it_accepts_a_buying_asset()
    {
        $offerEntry = (new OfferEntry())
            ->withBuyingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')));

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(Asset::class, $offerEntry->getBuyingAsset());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_int64_as_an_amount()
    {
        $offerEntry = (new OfferEntry())->withAmount(Int64::of(1000000));

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(Int64::class, $offerEntry->getAmount());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_a_scaled_amount_as_an_amount()
    {
        $offerEntry = (new OfferEntry())->withAmount(ScaledAmount::of('0.1'));

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(Int64::class, $offerEntry->getAmount());
        $this->assertEquals('1000000', $offerEntry->getAmount()->toNativeString());
    }

    /**
     * @test
     * @covers ::withPrice
     * @covers ::getPrice
     */
    public function it_accepts_a_price()
    {
        $offerEntry = (new OfferEntry())
            ->withPrice(Price::of(1, 2));

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(Price::class, $offerEntry->getPrice());
    }

    /**
     * @test
     * @covers ::withFlags
     * @covers ::getFlags
     */
    public function it_accepts_flags()
    {
        $offerEntry = (new OfferEntry())->withFlags(UInt32::of(1));

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(UInt32::class, $offerEntry->getFlags());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $offerEntry = (new OfferEntry())->withExtension(OfferEntryExt::empty());

        $this->assertInstanceOf(OfferEntry::class, $offerEntry);
        $this->assertInstanceOf(OfferEntryExt::class, $offerEntry->getExtension());
    }
}
