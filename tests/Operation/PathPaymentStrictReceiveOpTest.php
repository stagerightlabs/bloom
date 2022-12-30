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
use StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveOp;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveOp
 */
class PathPaymentStrictReceiveOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = PathPaymentStrictReceiveOp::operation(
            sendingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            sendingMaximum: '2',
            destination: 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            destinationAsset: 'ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            destinationAmount: '1',
            path: PathPaymentAssetList::empty(),
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $pathPaymentStrictSendOp = new PathPaymentStrictReceiveOp();
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withSendingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withSendingMaximum('2');
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withDestination('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withDestinationAsset('ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($pathPaymentStrictSendOp->isReady());

        $pathPaymentStrictSendOp = $pathPaymentStrictSendOp->withDestinationAmount('1');
        $this->assertTrue($pathPaymentStrictSendOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new PathPaymentStrictReceiveOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingAsset($asset)
            ->withSendingMaximum(Int64::of(10000000))
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationAsset($asset)
            ->withDestinationAmount(Int64::of(20000000));
        $buffer = XDR::fresh()->write($pathPaymentStrictReceiveOp);

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
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingMaximum(Int64::of(10000000))
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationAsset($asset)
            ->withDestinationAmount(Int64::of(20000000));
        XDR::fresh()->write($pathPaymentStrictReceiveOp);
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
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingAsset($asset)
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationAsset($asset)
            ->withDestinationAmount(Int64::of(20000000));
        XDR::fresh()->write($pathPaymentStrictReceiveOp);
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
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingAsset($asset)
            ->withSendingMaximum(Int64::of(10000000))
            ->withDestinationAsset($asset)
            ->withDestinationAmount(Int64::of(20000000));
        XDR::fresh()->write($pathPaymentStrictReceiveOp);
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
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingAsset($asset)
            ->withSendingMaximum(Int64::of(10000000))
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationAmount(Int64::of(20000000));
        XDR::fresh()->write($pathPaymentStrictReceiveOp);
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
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingAsset($asset)
            ->withSendingMaximum(Int64::of(10000000))
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDestinationAsset($asset);
        XDR::fresh()->write($pathPaymentStrictReceiveOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $pathPaymentStrictReceiveOp = XDR::fromBase64('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAJiWgAAAAABqbUHHOUNTgIZpeXjQQHgNYcXOazSxcCrhBCh2M4Od7gAAAAFBQkNEAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpSAAAAAAExLQAAAAAA')
            ->read(PathPaymentStrictReceiveOp::class);

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictReceiveOp->getSendingAsset());
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictReceiveOp->getSendingMaximum());
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictReceiveOp->getDestination());
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictReceiveOp->getDestinationAsset());
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictReceiveOp->getDestinationAmount());
        $this->assertInstanceOf(PathPaymentAssetList::class, $pathPaymentStrictReceiveOp->getPath());
    }

    /**
     * @test
     * @covers ::withSendingAsset
     * @covers ::getSendingAsset
     */
    public function it_accepts_a_sending_asset()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingAsset(new Asset());

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictReceiveOp->getSendingAsset());
    }

    /**
     * @test
     * @covers ::withSendingAsset
     * @covers ::getSendingAsset
     */
    public function it_accepts_a_sending_asset_string()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictReceiveOp->getSendingAsset());
    }

    /**
     * @test
     * @covers ::withSendingMaximum
     * @covers ::getSendingMaximum
     */
    public function it_accepts_an_int_64_as_a_sending_maximum()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingMaximum(Int64::of(10000000));

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictReceiveOp->getSendingMaximum());
    }

    /**
     * @test
     * @covers ::withSendingMaximum
     * @covers ::getSendingMaximum
     */
    public function it_accepts_a_scaled_amount_as_a_sending_maximum()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withSendingMaximum(ScaledAmount::of(1));

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictReceiveOp->getSendingMaximum());
        $this->assertEquals('10000000', $pathPaymentStrictReceiveOp->getSendingMaximum()->toNativeString());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_a_muxed_account_as_a_destination()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictReceiveOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_account_id_as_a_destination()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withDestination(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictReceiveOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     */
    public function it_rejects_invalid_account_ids()
    {
        $this->expectException(InvalidArgumentException::class);
        (new PathPaymentStrictReceiveOp())->withDestination(new AccountId());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_addressable_as_a_destination()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withDestination(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictReceiveOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_a_string_address_as_a_destination()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withDestination('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(MuxedAccount::class, $pathPaymentStrictReceiveOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestinationAsset
     * @covers ::getDestinationAsset
     */
    public function it_accepts_a_destination_asset()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withDestinationAsset(new Asset());

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictReceiveOp->getDestinationAsset());
    }

    /**
     * @test
     * @covers ::withDestinationAsset
     * @covers ::getDestinationAsset
     */
    public function it_accepts_a_destination_asset_string()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withDestinationAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictReceiveOp->getDestinationAsset());
    }

    /**
     * @test
     * @covers ::withDestinationAmount
     * @covers ::getDestinationAmount
     */
    public function it_accepts_an_int_64_as_a_destination_amount()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withDestinationAmount(Int64::of(20000000));

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictReceiveOp->getDestinationAmount());
    }

    /**
     * @test
     * @covers ::withDestinationAmount
     * @covers ::getDestinationAmount
     */
    public function it_accepts_a_scaled_amount_as_a_destination_amount()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withDestinationAmount(ScaledAmount::of(2));

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(Int64::class, $pathPaymentStrictReceiveOp->getDestinationAmount());
        $this->assertEquals('20000000', $pathPaymentStrictReceiveOp->getDestinationAmount()->toNativeString());
    }

    /**
     * @test
     * @covers ::withPath
     * @covers ::getPath
     */
    public function it_accepts_a_list_of_assets_as_a_payment_path()
    {
        $pathPaymentStrictReceiveOp = (new PathPaymentStrictReceiveOp())
            ->withPath(PathPaymentAssetList::empty());

        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $pathPaymentStrictReceiveOp);
        $this->assertInstanceOf(PathPaymentAssetList::class, $pathPaymentStrictReceiveOp->getPath());
    }
}
