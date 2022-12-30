<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\SimplePaymentResult;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\SimplePaymentResult
 */
class SimplePaymentResultTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $simplePaymentResult = (new SimplePaymentResult())
            ->withDestination(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withAsset(Asset::native())
            ->withAmount(Int64::of(1));
        $buffer = XDR::fresh()->write($simplePaymentResult);

        $this->assertEquals('AAAAAKoGr0kshnIfH0OINoWUgAOLadpx/z8ETHii5IyVnFUDAAAAAAAAAAAAAAAB', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_destination_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $simplePaymentResult = (new SimplePaymentResult())
            ->withAsset(Asset::native())
            ->withAmount(Int64::of(1));
        XDR::fresh()->write($simplePaymentResult);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_asset_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $simplePaymentResult = (new SimplePaymentResult())
            ->withDestination(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withAmount(Int64::of(1));
        XDR::fresh()->write($simplePaymentResult);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_amount_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $simplePaymentResult = (new SimplePaymentResult())
            ->withDestination(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withAsset(Asset::native());
        XDR::fresh()->write($simplePaymentResult);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $simplePaymentResult = XDR::fromBase64('AAAAAKoGr0kshnIfH0OINoWUgAOLadpx/z8ETHii5IyVnFUDAAAAAAAAAAAAAAAB')
            ->read(SimplePaymentResult::class);

        $this->assertInstanceOf(SimplePaymentResult::class, $simplePaymentResult);
        $this->assertEquals(
            'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ',
            $simplePaymentResult->getDestination()->getAddress()
        );
        $this->assertTrue($simplePaymentResult->getAmount()->isEqualTo(1));
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_account_id_as_a_destination()
    {
        $simplePaymentResult = (new SimplePaymentResult())
            ->withDestination(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'));

        $this->assertInstanceOf(AccountId::class, $simplePaymentResult->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_addressable_id_as_a_destination()
    {
        $simplePaymentResult = (new SimplePaymentResult())
            ->withDestination(Account::fromAddress('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'));

        $this->assertInstanceOf(AccountId::class, $simplePaymentResult->getDestination());
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_string_as_a_destination()
    {
        $simplePaymentResult = (new SimplePaymentResult())
            ->withDestination('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ');

        $this->assertInstanceOf(AccountId::class, $simplePaymentResult->getDestination());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_an_asset()
    {
        $simplePaymentResult = (new SimplePaymentResult())->withAsset(Asset::native());
        $this->assertInstanceOf(Asset::class, $simplePaymentResult->getAsset());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_int64_as_an_amount()
    {
        $simplePaymentResult = (new SimplePaymentResult())->withAmount(Int64::of(1));
        $this->assertInstanceOf(Int64::class, $simplePaymentResult->getAmount());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_a_scaled_amount_as_an_amount()
    {
        $simplePaymentResult = (new SimplePaymentResult())->withAmount(ScaledAmount::of(1));
        $this->assertInstanceOf(Int64::class, $simplePaymentResult->getAmount());
        $this->assertEquals('10000000', $simplePaymentResult->getAmount()->toNativeString());
    }
}
