<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\OfferEntry;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\Offer\ClaimAtomList;
use StageRightLabs\Bloom\Operation\ManageOfferSuccessResult;
use StageRightLabs\Bloom\Operation\ManageOfferSuccessResultOffer;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageOfferSuccessResult
 */
class ManageOfferSuccessResultTest extends TestCase
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
        $offer = ManageOfferSuccessResultOffer::wrapCreatedOffer($offerEntry);
        $manageOfferSuccessResult = (new ManageOfferSuccessResult())
            ->withOffer($offer);
        $buffer = XDR::fresh()->write($manageOfferSuccessResult);

        $this->assertEquals(
            'AAAAAAAAAAAAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAAAAAAQAAAAFBQkNEAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpSAAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAA9CQAAAAAEAAAACAAAAAAAAAAA=',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_offer_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new ManageOfferSuccessResult());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $manageOfferSuccessResult = XDR::fromBase64('AAAAAAAAAAAAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAAAAAAQAAAAFBQkNEAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpSAAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAA9CQAAAAAEAAAACAAAAAAAAAAA=')
            ->read(ManageOfferSuccessResult::class);

        $this->assertInstanceOf(ManageOfferSuccessResult::class, $manageOfferSuccessResult);
        $this->assertInstanceOf(ClaimAtomList::class, $manageOfferSuccessResult->getOffersClaimed());
        $this->assertInstanceOf(ManageOfferSuccessResultOffer::class, $manageOfferSuccessResult->getOffer());
    }

    /**
     * @test
     * @covers ::withOffersClaimed
     * @covers ::getOffersClaimed
     */
    public function it_accepts_a_list_of_offers_claimed()
    {
        $manageOfferSuccessResult = (new ManageOfferSuccessResult())
            ->withOffersClaimed(ClaimAtomList::empty());

        $this->assertInstanceOf(ManageOfferSuccessResult::class, $manageOfferSuccessResult);
        $this->assertInstanceOf(ClaimAtomList::class, $manageOfferSuccessResult->getOffersClaimed());
    }

    /**
     * @test
     * @covers ::withOffer
     * @covers ::getOffer
     */
    public function it_accepts_an_offer()
    {
        $offerEntry = (new OfferEntry())
            ->withSellerAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withOfferId(Int64::of(1))
            ->withSellingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withBuyingAsset(Asset::of(AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')))
            ->withAmount(Int64::of(1000000))
            ->withPrice(Price::of(1, 2));
        $offer = ManageOfferSuccessResultOffer::wrapCreatedOffer($offerEntry);
        $manageOfferSuccessResult = (new ManageOfferSuccessResult())
            ->withOffer($offer);

        $this->assertInstanceOf(ManageOfferSuccessResult::class, $manageOfferSuccessResult);
        $this->assertInstanceOf(ManageOfferSuccessResultOffer::class, $manageOfferSuccessResult->getOffer());
    }
}
