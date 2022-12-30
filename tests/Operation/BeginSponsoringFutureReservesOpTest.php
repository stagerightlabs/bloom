<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesOp
 */
class BeginSponsoringFutureReservesOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = BeginSponsoringFutureReservesOp::operation(
            sponsoredId: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(BeginSponsoringFutureReservesOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $beginSponsoringFutureReservesOp = new BeginSponsoringFutureReservesOp();
        $this->assertFalse($beginSponsoringFutureReservesOp->isReady());

        $beginSponsoringFutureReservesOp = $beginSponsoringFutureReservesOp->withSponsoredId('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');
        $this->assertTrue($beginSponsoringFutureReservesOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new BeginSponsoringFutureReservesOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $sponsoredId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $beginSponsoringFutureReservesOp = (new BeginSponsoringFutureReservesOp())
            ->withSponsoredId($sponsoredId);
        $buffer = XDR::fresh()->write($beginSponsoringFutureReservesOp);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53u', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_sponsored_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new BeginSponsoringFutureReservesOp());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $beginSponsoringFutureReservesOp = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53u')
            ->read(BeginSponsoringFutureReservesOp::class);

        $this->assertInstanceOf(BeginSponsoringFutureReservesOp::class, $beginSponsoringFutureReservesOp);
        $this->assertInstanceOf(AccountId::class, $beginSponsoringFutureReservesOp->getSponsoredId());
    }

    /**
     * @test
     * @covers ::withSponsoredId
     * @covers ::getSponsoredId
     */
    public function it_accepts_a_sponsored_id()
    {
        $sponsoredId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $beginSponsoringFutureReservesOp = (new BeginSponsoringFutureReservesOp())
            ->withSponsoredId($sponsoredId);

        $this->assertInstanceOf(BeginSponsoringFutureReservesOp::class, $beginSponsoringFutureReservesOp);
        $this->assertInstanceOf(AccountId::class, $beginSponsoringFutureReservesOp->getSponsoredId());
    }
}
