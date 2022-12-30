<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Offer\ClaimAtomList;
use StageRightLabs\Bloom\Operation\PathPaymentStrictSendResultSuccess;
use StageRightLabs\Bloom\Operation\SimplePaymentResult;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PathPaymentStrictSendResultSuccess
 */
class PathPaymentStrictSendResultSuccessTest extends TestCase
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
        $pathPaymentStrictSendResultSuccess = (new PathPaymentStrictSendResultSuccess())
            ->withPaymentResult($simplePaymentResult);
        $buffer = XDR::fresh()->write($pathPaymentStrictSendResultSuccess);

        $this->assertEquals('AAAAAAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAAAAAAAAQ==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_payment_result_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new PathPaymentStrictSendResultSuccess());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $pathPaymentStrictSendResultSuccess = XDR::fromBase64('AAAAAAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAAAAAAAAQ==')
            ->read(PathPaymentStrictSendResultSuccess::class);

        $this->assertInstanceOf(PathPaymentStrictSendResultSuccess::class, $pathPaymentStrictSendResultSuccess);
        $this->assertInstanceOf(ClaimAtomList::class, $pathPaymentStrictSendResultSuccess->getOffers());
        $this->assertInstanceOf(SimplePaymentResult::class, $pathPaymentStrictSendResultSuccess->getPaymentResult());
    }

    /**
     * @test
     * @covers ::withOffers
     * @covers ::getOffers
     */
    public function it_accepts_a_list_of_offers()
    {
        $pathPaymentStrictSendResultSuccess = (new PathPaymentStrictSendResultSuccess())
            ->withOffers(ClaimAtomList::empty());

        $this->assertInstanceOf(PathPaymentStrictSendResultSuccess::class, $pathPaymentStrictSendResultSuccess);
        $this->assertInstanceOf(ClaimAtomList::class, $pathPaymentStrictSendResultSuccess->getOffers());
    }

    /**
     * @test
     * @covers ::withPaymentResult
     * @covers ::getPaymentResult
     */
    public function it_accepts_a_payment_result()
    {
        $simplePaymentResult = (new SimplePaymentResult())
            ->withDestination(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withAsset(Asset::native())
            ->withAmount(Int64::of(1));
        $pathPaymentStrictSendResultSuccess = (new PathPaymentStrictSendResultSuccess())
            ->withPaymentResult($simplePaymentResult);

        $this->assertInstanceOf(PathPaymentStrictSendResultSuccess::class, $pathPaymentStrictSendResultSuccess);
        $this->assertInstanceOf(SimplePaymentResult::class, $pathPaymentStrictSendResultSuccess->getPaymentResult());
    }
}
