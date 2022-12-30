<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\PathPaymentAssetList;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\PathPaymentStrictSendOp;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PathPaymentStrictSendOp
 */
class PathPaymentStrictSendOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = PathPaymentStrictSendOp::operation(
            sendingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            sendingAmount: '2',
            destination: 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            destinationAsset: 'ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            destinationMinimum: '1',
            path: PathPaymentAssetList::empty(),
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $pathPaymentStrictSendOp = new PathPaymentStrictSendOp();
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withSendingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withSendingAmount('2');
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withDestination('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withDestinationAsset('ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withDestinationMinimum('1');
        $this->assertTrue($pathPaymentStrictSendOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new PathPaymentStrictSendOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAsset($asset)
            ->withSendingAmount(Int64::of(10000000))
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationAsset($asset)
            ->withDestinationMinimum(Int64::of(20000000));
        $buffer = XDR::fresh()->write($pathPaymentStrictSendOp);

        $this->assertEquals(
            'AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAJiWgAAAAABqbUHHOUNTgIZpeXjQQHgNYcXOazSxcCrhBCh2M4Od7gAAAAFBQkNEAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpSAAAAAAExLQAAAAAA',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_sending_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAmount(Int64::of(10000000))
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationAsset($asset)
            ->withDestinationMinimum(Int64::of(20000000));
        XDR::fresh()->write($pathPaymentStrictSendOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_sending_max_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAsset($asset)
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationAsset($asset)
            ->withDestinationMinimum(Int64::of(20000000));
        XDR::fresh()->write($pathPaymentStrictSendOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_destination_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAsset($asset)
            ->withSendingAmount(Int64::of(10000000))
            ->withDestinationAsset($asset)
            ->withDestinationMinimum(Int64::of(20000000));
        XDR::fresh()->write($pathPaymentStrictSendOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_destination_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAsset($asset)
            ->withSendingAmount(Int64::of(10000000))
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationMinimum(Int64::of(20000000));
        XDR::fresh()->write($pathPaymentStrictSendOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_destination_amount_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAsset($asset)
            ->withSendingAmount(Int64::of(10000000))
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationAsset($asset);
        XDR::fresh()->write($pathPaymentStrictSendOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $pathPaymentStrictSendOp = XDR::fromBase64('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAJiWgAAAAABqbUHHOUNTgIZpeXjQQHgNYcXOazSxcCrhBCh2M4Od7gAAAAFBQkNEAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpSAAAAAAExLQAAAAAA')
            ->read(PathPaymentStrictSendOp::class);

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictSendOp->getSendingAsset());
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictSendOp->getSendingAmount());
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictSendOp->getDestination());
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictSendOp->getDestinationAsset());
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictSendOp->getDestinationMinimum());
        $this->assertInstanceOf(PathPaymentAssetList::class, $pathPaymentStrictSendOp->getPath());
    }

    /**
     * @test
     * @covers ::withSendingAsset
     * @covers ::getSendingAsset
     */
    public function it_accepts_a_sending_asset()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAsset(new Asset());

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictSendOp->getSendingAsset());
    }

    /**
     * @test
     * @covers ::withSendingAsset
     * @covers ::getSendingAsset
     */
    public function it_accepts_a_sending_asset_string()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictSendOp->getSendingAsset());
    }

    /**
     * @test
     * @covers ::withSendingAmount
     * @covers ::getSendingAmount
     */
    public function it_accepts_an_int_64_as_a_sending_maximum()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAmount(Int64::of(10000000));

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictSendOp->getSendingAmount());
    }

    /**
     * @test
     * @covers ::withSendingAmount
     * @covers ::getSendingAmount
     */
    public function it_accepts_a_scaled_amount_as_a_sending_maximum()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withSendingAmount(ScaledAmount::of(1));

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictSendOp->getSendingAmount());
        $this->assertEquals('10000000', $pathPaymentStrictSendOp->getSendingAmount()->toNativeString());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_a_muxed_account_as_a_destination()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictSendOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_account_id_as_a_destination()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withDestination(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictSendOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     */
    public function it_rejects_invalid_account_ids()
    {
        $this->expectException(InvalidArgumentException::class);
        (new PathPaymentStrictSendOp())->withDestination(new AccountId());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_addressable_as_a_destination()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withDestination(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictSendOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_a_string_address_as_a_destination()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withDestination('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictSendOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestinationAsset
     * @covers ::getDestinationAsset
     */
    public function it_accepts_a_destination_asset()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withDestinationAsset(new Asset());

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictSendOp->getDestinationAsset());
    }

    /**
     * @test
     * @covers ::withDestinationAsset
     * @covers ::getDestinationAsset
     */
    public function it_accepts_a_destination_asset_string()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withDestinationAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictSendOp->getDestinationAsset());
    }

    /**
     * @test
     * @covers ::withDestinationMinimum
     * @covers ::getDestinationMinimum
     */
    public function it_accepts_an_int_64_as_a_destination_amount()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withDestinationMinimum(Int64::of(20000000));

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictSendOp->getDestinationMinimum());
    }

    /**
     * @test
     * @covers ::withDestinationMinimum
     * @covers ::getDestinationMinimum
     */
    public function it_accepts_a_scaled_amount_as_a_destination_amount()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withDestinationMinimum(ScaledAmount::of(2));

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictSendOp->getDestinationMinimum());
        $this->assertEquals('20000000', $pathPaymentStrictSendOp->getDestinationMinimum()->toNativeString());
    }

    /**
     * @test
     * @covers ::withPath
     * @covers ::getPath
     */
    public function it_accepts_a_list_of_assets_as_a_payment_path()
    {
        $pathPaymentStrictSendOp = (new PathPaymentStrictSendOp())
            ->withPath(PathPaymentAssetList::empty());

        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $pathPaymentStrictSendOp);
        $this->assertInstanceOf(PathPaymentAssetList::class, $pathPaymentStrictSendOp->getPath());
    }
}
