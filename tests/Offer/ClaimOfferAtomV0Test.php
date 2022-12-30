<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Offer;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Offer\ClaimOfferAtomV0;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Offer\ClaimOfferAtomV0
 */
class ClaimOfferAtomV0Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())
            ->withSellerEd25519(UInt256::of('1'))
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        $buffer = XDR::fresh()->write($claimOfferAtomV0);

        $this->assertEquals('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADEAAAAAAAAAAgAAAAFBQkNEAAAAAKoGr0kshnIfH0OINoWUgAOLadpx/z8ETHii5IyVnFUDAAAAAAAAAAMAAAABRUZHSAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAAE', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_seller_ed25519_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtomV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_offer_id_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())
            ->withSellerEd25519(UInt256::of('1'))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtomV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_asset_sold_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())
            ->withSellerEd25519(UInt256::of('1'))
            ->withOfferId(Int64::of(2))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtomV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_amount_sold_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())
            ->withSellerEd25519(UInt256::of('1'))
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtomV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_asset_bought_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())
            ->withSellerEd25519(UInt256::of('1'))
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimOfferAtomV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_amount_bought_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())
            ->withSellerEd25519(UInt256::of('1'))
            ->withOfferId(Int64::of(2))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')));
        XDR::fresh()->write($claimOfferAtomV0);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $claimOfferAtomV0 = XDR::fromBase64('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADEAAAAAAAAAAgAAAAFBQkNEAAAAAKoGr0kshnIfH0OINoWUgAOLadpx/z8ETHii5IyVnFUDAAAAAAAAAAMAAAABRUZHSAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAAE')
            ->read(ClaimOfferAtomV0::class);

        $this->assertInstanceOf(ClaimOfferAtomV0::class, $claimOfferAtomV0);
        $this->assertTrue($claimOfferAtomV0->getOfferId()->isEqualTo(2));
        $this->assertTrue($claimOfferAtomV0->getAmountSold()->isEqualTo(3));
        $this->assertTrue($claimOfferAtomV0->getAmountBought()->isEqualTo(4));
    }

    /**
     * @test
     * @covers ::withSellerEd25519
     * @covers ::getSellerEd25519
     */
    public function it_accepts_a_seller_ed25519()
    {
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())->withSellerEd25519(UInt256::of('1'));

        $this->assertInstanceOf(UInt256::class, $claimOfferAtomV0->getSellerEd25519());
        $this->assertEquals(
            UInt256::of('1')->getBytes(),
            $claimOfferAtomV0->getSellerEd25519()->getBytes()
        );
    }

    /**
     * @test
     * @covers ::withOfferId
     * @covers ::getOfferId
     */
    public function it_accepts_an_offer_id()
    {
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())->withOfferId(Int64::of(2));

        $this->assertInstanceOf(Int64::class, $claimOfferAtomV0->getOfferId());
        $this->assertTrue($claimOfferAtomV0->getOfferId()->isEqualTo(2));
    }

    /**
     * @test
     * @covers ::withAssetSold
     * @covers ::getAssetSold
     */
    public function it_accepts_an_asset_sold()
    {
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())->withAssetSold(
            Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
        );

        $this->assertInstanceOf(Asset::class, $claimOfferAtomV0->getAssetSold());
        $this->assertEquals('ABCD', $claimOfferAtomV0->getAssetSold()->unwrap()->getAssetCode());
    }

    /**
     * @test
     * @covers ::withAmountSold
     * @covers ::getAmountSold
     */
    public function it_accepts_an_int64_as_an_amount_sold()
    {
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())->withAmountSold(Int64::of(3));

        $this->assertInstanceOf(Int64::class, $claimOfferAtomV0->getAmountSold());
        $this->assertTrue($claimOfferAtomV0->getAmountSold()->isEqualTo(3));
    }

    /**
     * @test
     * @covers ::withAmountSold
     * @covers ::getAmountSold
     */
    public function it_accepts_a_scaled_amount_as_an_amount_sold()
    {
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())->withAmountSold(ScaledAmount::of(3));

        $this->assertInstanceOf(Int64::class, $claimOfferAtomV0->getAmountSold());
        $this->assertEquals('30000000', $claimOfferAtomV0->getAmountSold()->toNativeString());
    }

    /**
     * @test
     * @covers ::withAssetBought
     * @covers ::getAssetBought
     */
    public function it_accepts_an_asset_bought()
    {
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())->withAssetBought(
            Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
        );

        $this->assertInstanceOf(Asset::class, $claimOfferAtomV0->getAssetBought());
        $this->assertEquals('EFGH', $claimOfferAtomV0->getAssetBought()->unwrap()->getAssetCode());
    }

    /**
     * @test
     * @covers ::withAmountBought
     * @covers ::getAmountBought
     */
    public function it_accepts_an_int64_as_an_amount_bought()
    {
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())->withAmountBought(Int64::of(4));

        $this->assertInstanceOf(Int64::class, $claimOfferAtomV0->getAmountBought());
        $this->assertTrue($claimOfferAtomV0->getAmountBought()->isEqualTo(4));
    }

    /**
     * @test
     * @covers ::withAmountBought
     * @covers ::getAmountBought
     */
    public function it_accepts_a_scaled_amount_as_an_amount_bought()
    {
        $claimOfferAtomV0 = (new ClaimOfferAtomV0())->withAmountBought(ScaledAmount::of(4));

        $this->assertInstanceOf(Int64::class, $claimOfferAtomV0->getAmountBought());
        $this->assertEquals('40000000', $claimOfferAtomV0->getAmountBought()->toNativeString());
    }
}
