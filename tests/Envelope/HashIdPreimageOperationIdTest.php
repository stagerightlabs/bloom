<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Envelope;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Envelope\HashIdPreimageOperationId;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Envelope\HashIdPreimageOperationId
 */
class HashIdPreimageOperationIdTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $hashIdPreimageOperationId = (new HashIdPreimageOperationId())
            ->withSourceAccount($sourceAccount)
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withOperationNumber(UInt32::of(2));
        $buffer = XDR::fresh()->write($hashIdPreimageOperationId);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAAC', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_source_account_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $hashIdPreimageOperationId = (new HashIdPreimageOperationId())
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withOperationNumber(UInt32::of(2));
        XDR::fresh()->write($hashIdPreimageOperationId);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_sequence_number_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $hashIdPreimageOperationId = (new HashIdPreimageOperationId())
            ->withSourceAccount($sourceAccount)
            ->withOperationNumber(UInt32::of(2));
        XDR::fresh()->write($hashIdPreimageOperationId);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_operation_number_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $hashIdPreimageOperationId = (new HashIdPreimageOperationId())
            ->withSourceAccount($sourceAccount)
            ->withSequenceNumber(SequenceNumber::of(1));
        XDR::fresh()->write($hashIdPreimageOperationId);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $hashIdPreimageOperationId = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAAC')
            ->read(HashIdPreimageOperationId::class);

        $this->assertInstanceOf(HashIdPreimageOperationId::class, $hashIdPreimageOperationId);
        $this->assertInstanceOf(AccountId::class, $hashIdPreimageOperationId->getSourceAccount());
        $this->assertInstanceOf(SequenceNumber::class, $hashIdPreimageOperationId->getSequenceNumber());
        $this->assertInstanceOf(UInt32::class, $hashIdPreimageOperationId->getOperationNumber());
    }

    /**
     * @test
     * @covers ::withSourceAccount
     * @covers ::getSourceAccount
     */
    public function it_accepts_a_source_account()
    {
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $hashIdPreimageOperationId = (new HashIdPreimageOperationId())
            ->withSourceAccount($sourceAccount);

        $this->assertInstanceOf(HashIdPreimageOperationId::class, $hashIdPreimageOperationId);
        $this->assertInstanceOf(AccountId::class, $hashIdPreimageOperationId->getSourceAccount());
    }

    /**
     * @test
     * @covers ::withSequenceNumber
     * @covers ::getSequenceNumber
     */
    public function it_accepts_a_sequence_number()
    {
        $hashIdPreimageOperationId = (new HashIdPreimageOperationId())
            ->withSequenceNumber(SequenceNumber::of(1));

        $this->assertInstanceOf(HashIdPreimageOperationId::class, $hashIdPreimageOperationId);
        $this->assertInstanceOf(SequenceNumber::class, $hashIdPreimageOperationId->getSequenceNumber());
    }

    /**
     * @test
     * @covers ::withOperationNumber
     * @covers ::getOperationNumber
     */
    public function it_accepts_an_operation_number()
    {
        $hashIdPreimageOperationId = (new HashIdPreimageOperationId())
            ->withOperationNumber(UInt32::of(2));

        $this->assertInstanceOf(HashIdPreimageOperationId::class, $hashIdPreimageOperationId);
        $this->assertInstanceOf(UInt32::class, $hashIdPreimageOperationId->getOperationNumber());
    }
}
