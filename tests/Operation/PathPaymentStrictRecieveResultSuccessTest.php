<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Offer\ClaimAtomList;
use StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveResultSuccess;
use StageRightLabs\Bloom\Operation\SimplePaymentResult;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveResultSuccess
 */
class PathPaymentStrictReceiveResultSuccessTest extends TestCase
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
        $pathPaymentStrictReceiveResult = (new PathPaymentStrictReceiveResultSuccess())
            ->withClaimAtomList(ClaimAtomList::empty())
            ->withLast($simplePaymentResult);
        $buffer = XDR::fresh()->write($pathPaymentStrictReceiveResult);

        $this->assertEquals('AAAAAAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAAAAAAAAQ==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_claim_list_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $simplePaymentResult = (new SimplePaymentResult())
            ->withDestination(AccountId::fromAddressable('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
            ->withAsset(Asset::native())
            ->withAmount(Int64::of(1));
        $pathPaymentStrictReceiveResult = (new PathPaymentStrictReceiveResultSuccess())
            ->withLast($simplePaymentResult);
        XDR::fresh()->write($pathPaymentStrictReceiveResult);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_simple_payment_result_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $pathPaymentStrictReceiveResult = (new PathPaymentStrictReceiveResultSuccess())
            ->withClaimAtomList(ClaimAtomList::empty());
        XDR::fresh()->write($pathPaymentStrictReceiveResult);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $pathPaymentStrictReceiveResult = XDR::fromBase64('AAAAAAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAAAAAAAAQ==')
            ->read(PathPaymentStrictReceiveResultSuccess::class);

        $this->assertInstanceOf(PathPaymentStrictReceiveResultSuccess::class, $pathPaymentStrictReceiveResult);
        $this->assertInstanceOf(ClaimAtomList::class, $pathPaymentStrictReceiveResult->getClaimAtomList());
        $this->assertInstanceOf(SimplePaymentResult::class, $pathPaymentStrictReceiveResult->getLast());
    }

    /**
     * @test
     * @covers ::withClaimAtomList
     * @covers ::getClaimAtomList
     */
    public function it_accepts_a_claim_atom_list()
    {
        $pathPaymentStrictReceiveResult = (new PathPaymentStrictReceiveResultSuccess())
            ->withClaimAtomList(ClaimAtomList::empty());

        $this->assertInstanceOf(ClaimAtomList::class, $pathPaymentStrictReceiveResult->getClaimAtomList());
    }

    /**
     * @test
     * @covers ::withLast
     * @covers ::getLast
     */
    public function it_accepts_a_simple_payment_result()
    {
        $simplePaymentResult = new SimplePaymentResult();
        $pathPaymentStrictReceiveResult = (new PathPaymentStrictReceiveResultSuccess())
            ->withLast($simplePaymentResult);

        $this->assertInstanceOf(SimplePaymentResult::class, $pathPaymentStrictReceiveResult->getLast());
    }
}
