<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\PaymentOp;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PaymentOp
 */
class PaymentOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = PaymentOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            asset: Asset::native(),
            amount: '10',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(PaymentOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $paymentOp = new PaymentOp();
        $this->assertFalse($paymentOp->isReady());

        $paymentOp = $paymentOp->withDestination('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');
        $this->assertFalse($paymentOp->isReady());

        $paymentOp = $paymentOp->withAsset(Asset::native());
        $this->assertFalse($paymentOp->isReady());

        $paymentOp = $paymentOp->withAmount('10');
        $this->assertTrue($paymentOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new PaymentOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $paymentOp = (new PaymentOp())
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withAsset($asset)
            ->withAmount(Int64::of(10000000));
        $buffer = XDR::fresh()->write($paymentOp);

        $this->assertEquals(
            'AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAJiWgA==',
            $buffer->toBase64()
        );
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
        $paymentOp = (new PaymentOp())
            ->withAsset($asset)
            ->withAmount(Int64::of(10000000));
        XDR::fresh()->write($paymentOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $paymentOp = (new PaymentOp())
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withAmount(Int64::of(10000000));
        XDR::fresh()->write($paymentOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_amount_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $paymentOp = (new PaymentOp())
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withAsset($asset);
        XDR::fresh()->write($paymentOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $paymentOp = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAJiWgA==')
            ->read(PaymentOp::class);

        $this->assertInstanceOf(PaymentOp::class, $paymentOp);
        $this->assertInstanceOf(MuxedAccount::class, $paymentOp->getDestination());
        $this->assertInstanceOf(Asset::class, $paymentOp->getAsset());
        $this->assertInstanceOf(Int64::class, $paymentOp->getAmount());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_a_muxed_account_as_a_destination()
    {
        $paymentOp = (new PaymentOp())
            ->withDestination(MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PaymentOp::class, $paymentOp);
        $this->assertInstanceOf(MuxedAccount::class, $paymentOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_account_id_as_a_destination()
    {
        $paymentOp = (new PaymentOp())
            ->withDestination(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PaymentOp::class, $paymentOp);
        $this->assertInstanceOf(MuxedAccount::class, $paymentOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     */
    public function it_rejects_invalid_account_ids()
    {
        $this->expectException(InvalidArgumentException::class);
        (new PaymentOp())->withDestination(new AccountId());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_addressable_as_a_destination()
    {
        $paymentOp = (new PaymentOp())
            ->withDestination(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PaymentOp::class, $paymentOp);
        $this->assertInstanceOf(MuxedAccount::class, $paymentOp->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_a_string_address_as_a_destination()
    {
        $paymentOp = (new PaymentOp())
            ->withDestination('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(PaymentOp::class, $paymentOp);
        $this->assertInstanceOf(MuxedAccount::class, $paymentOp->getDestination());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_an_asset()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $paymentOp = (new PaymentOp())->withAsset($asset);

        $this->assertInstanceOf(PaymentOp::class, $paymentOp);
        $this->assertInstanceOf(Asset::class, $paymentOp->getAsset());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_asset_strings()
    {
        $paymentOp = (new PaymentOp())->withAsset('ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(PaymentOp::class, $paymentOp);
        $this->assertInstanceOf(Asset::class, $paymentOp->getAsset());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_int_64_as_an_amount()
    {
        $paymentOp = (new PaymentOp())->withAmount(Int64::of(10000000));

        $this->assertInstanceOf(PaymentOp::class, $paymentOp);
        $this->assertInstanceOf(Int64::class, $paymentOp->getAmount());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_scaled_amount_as_an_amount()
    {
        $paymentOp = (new PaymentOp())->withAmount(ScaledAmount::of(1));

        $this->assertInstanceOf(PaymentOp::class, $paymentOp);
        $this->assertInstanceOf(Int64::class, $paymentOp->getAmount());
        $this->assertEquals('10000000', $paymentOp->getAmount()->toNativeString());
    }
}
