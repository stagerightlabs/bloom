<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Offer;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Offer\ClaimOfferAtom;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Offer\ClaimOfferAtom
 */
class ClaimOfferAtomTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $claimOfferAtom = (new ClaimOfferAtom())
            ->withSellerId(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        $buffer = XDR::fresh()->write($claimOfferAtom);

        $this->assertEquals('AAAAAKoGr0kshnIfH0OINoWUgAOLadpx/z8ETHii5IyVnFUDAAAAAAAAAAIAAAABQUJDRAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAADAAAAAUVGR0gAAAAAqgavSSyGch8fQ4g2hZSAA4tp2nH/PwRMeKLkjJWcVQMAAAAAAAAABA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_seller_id_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtom = (new ClaimOfferAtom())
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtom);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_offer_id_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtom = (new ClaimOfferAtom())
            ->withSellerId(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtom);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_asset_sold_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtom = (new ClaimOfferAtom())
            ->withSellerId(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withOfferId(Int64::of(2))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtom);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_amount_sold_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtom = (new ClaimOfferAtom())
            ->withSellerId(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtom);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_asset_bought_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtom = (new ClaimOfferAtom())
            ->withSellerId(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtom);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_amount_bought_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtom = (new ClaimOfferAtom())
            ->withSellerId(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')));
        XDR::fresh()->write($claimOfferAtom);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $claimOfferAtom = XDR::fromBase64('AAAAAKoGr0kshnIfH0OINoWUgAOLadpx/z8ETHii5IyVnFUDAAAAAAAAAAIAAAABQUJDRAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAADAAAAAUVGR0gAAAAAqgavSSyGch8fQ4g2hZSAA4tp2nH/PwRMeKLkjJWcVQMAAAAAAAAABA==')
            ->read(ClaimOfferAtom::class);

        $this->assertInstanceOf(ClaimOfferAtom::class, $claimOfferAtom);
        $this->assertTrue($claimOfferAtom->getOfferId()->isEqualTo(2));
        $this->assertTrue($claimOfferAtom->getAmountSold()->isEqualTo(3));
        $this->assertTrue($claimOfferAtom->getAmountBought()->isEqualTo(4));
    }

    /**
     * @test
     * @covers ::withSellerId
     * @covers ::getSellerId
     */
    public function it_accepts_a_seller_id()
    {
        $claimOfferAtom = (new ClaimOfferAtom())->withSellerId(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'));

        $this->assertInstanceOf(AccountId::class, $claimOfferAtom->getSellerId());
        $this->assertEquals(
            'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ',
            $claimOfferAtom->getSellerId()->getAddress(),
        );
    }

    /**
     * @test
     * @covers ::withOfferId
     * @covers ::getOfferId
     */
    public function it_accepts_an_offer_id()
    {
        $claimOfferAtom = (new ClaimOfferAtom())->withOfferId(Int64::of(2));

        $this->assertInstanceOf(Int64::class, $claimOfferAtom->getOfferId());
        $this->assertTrue($claimOfferAtom->getOfferId()->isEqualTo(2));
    }

    /**
     * @test
     * @covers ::withAssetSold
     * @covers ::getAssetSold
     */
    public function it_accepts_an_asset_sold()
    {
        $claimOfferAtom = (new ClaimOfferAtom())->withAssetSold(
            Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
        );

        $this->assertInstanceOf(Asset::class, $claimOfferAtom->getAssetSold());
        $this->assertEquals('ABCD', $claimOfferAtom->getAssetSold()->unwrap()->getAssetCode());
    }

    /**
     * @test
     * @covers ::withAmountSold
     * @covers ::getAmountSold
     */
    public function it_accepts_an_int64_as_an_amount_sold()
    {
        $claimOfferAtom = (new ClaimOfferAtom())->withAmountSold(Int64::of(3));

        $this->assertInstanceOf(Int64::class, $claimOfferAtom->getAmountSold());
        $this->assertTrue($claimOfferAtom->getAmountSold()->isEqualTo(3));
    }

    /**
     * @test
     * @covers ::withAmountSold
     * @covers ::getAmountSold
     */
    public function it_accepts_a_scaled_amount_as_an_amount_sold()
    {
        $claimOfferAtom = (new ClaimOfferAtom())->withAmountSold(ScaledAmount::of(3));

        $this->assertInstanceOf(Int64::class, $claimOfferAtom->getAmountSold());
        $this->assertEquals('30000000', $claimOfferAtom->getAmountSold()->toNativeString());
    }

    /**
     * @test
     * @covers ::withAssetBought
     * @covers ::getAssetBought
     */
    public function it_accepts_an_asset_bought()
    {
        $claimOfferAtom = (new ClaimOfferAtom())->withAssetBought(
            Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
        );

        $this->assertInstanceOf(Asset::class, $claimOfferAtom->getAssetBought());
        $this->assertEquals('EFGH', $claimOfferAtom->getAssetBought()->unwrap()->getAssetCode());
    }

    /**
     * @test
     * @covers ::withAmountBought
     * @covers ::getAmountBought
     */
    public function it_accepts_an_int64_as_an_amount_bought()
    {
        $claimOfferAtom = (new ClaimOfferAtom())->withAmountBought(Int64::of(4));

        $this->assertInstanceOf(Int64::class, $claimOfferAtom->getAmountBought());
        $this->assertTrue($claimOfferAtom->getAmountBought()->isEqualTo(4));
    }

    /**
     * @test
     * @covers ::withAmountBought
     * @covers ::getAmountBought
     */
    public function it_accepts_a_scaled_amount_as_an_amount_bought()
    {
        $claimOfferAtom = (new ClaimOfferAtom())->withAmountBought(ScaledAmount::of(4));

        $this->assertInstanceOf(Int64::class, $claimOfferAtom->getAmountBought());
        $this->assertEquals('40000000', $claimOfferAtom->getAmountBought()->toNativeString());
    }
}
