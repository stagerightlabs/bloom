<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use DateTime;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV3;
use StageRightLabs\Bloom\Primitives\ExtensionPoint;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\AccountEntryExtensionV3
 */
class AccountEntryExtensionV3Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $accountEntryExtensionV3 = (new AccountEntryExtensionV3())
            ->withSeqLedger(UInt32::of(1))
            ->withSeqTime(TimePoint::fromUnixEpoch(1641024000));
        $buffer = XDR::fresh()->write($accountEntryExtensionV3);

        $this->assertEquals('AAAAAAAAAAEAAAAAYdAKAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_sequence_ledger_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntryExtensionV3 = (new AccountEntryExtensionV3())
            ->withSeqTime(TimePoint::fromUnixEpoch(1641024000));
        XDR::fresh()->write($accountEntryExtensionV3);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_sequence_time_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntryExtensionV3 = (new AccountEntryExtensionV3())
            ->withSeqLedger(UInt32::of(1));
        XDR::fresh()->write($accountEntryExtensionV3);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $accountEntryExtensionV3 = XDR::fromBase64('AAAAAAAAAAEAAAAAYdAKAA==')
            ->read(AccountEntryExtensionV3::class);

        $this->assertInstanceOf(AccountEntryExtensionV3::class, $accountEntryExtensionV3);
        $this->assertInstanceOf(UInt32::class, $accountEntryExtensionV3->getSeqLedger());
        $this->assertInstanceOf(TimePoint::class, $accountEntryExtensionV3->getSeqTime());
    }

    /**
     * @test
     * @covers ::withExtensionPoint
     * @covers ::getExtensionPoint
     */
    public function it_accepts_an_extension_point()
    {
        $accountEntryExtensionV3 = (new AccountEntryExtensionV3())
            ->withExtensionPoint(ExtensionPoint::empty());

        $this->assertInstanceOf(ExtensionPoint::class, $accountEntryExtensionV3->getExtensionPoint());
    }

    /**
     * @test
     * @covers ::withSeqLedger
     * @covers ::getSeqLedger
     */
    public function it_accepts_a_sequence_ledger()
    {
        $accountEntryExtensionV3 = (new AccountEntryExtensionV3())
            ->withSeqLedger(UInt32::of(1));

        $this->assertInstanceOf(UInt32::class, $accountEntryExtensionV3->getSeqLedger());
    }

    /**
     * @test
     * @covers ::withSeqTime
     * @covers ::getSeqTime
     */
    public function it_accepts_a_date_string_as_sequence_time()
    {
        $accountEntryExtensionV3 = (new AccountEntryExtensionV3())
            ->withSeqTime('2022-01-01');

        $this->assertInstanceOf(TimePoint::class, $accountEntryExtensionV3->getSeqTime());
    }

    /**
     * @test
     * @covers ::withSeqTime
     * @covers ::getSeqTime
     */
    public function it_accepts_a_unix_epoch_as_sequence_time()
    {
        $accountEntryExtensionV3 = (new AccountEntryExtensionV3())
            ->withSeqTime(1641024000);

        $this->assertInstanceOf(TimePoint::class, $accountEntryExtensionV3->getSeqTime());
    }

    /**
     * @test
     * @covers ::withSeqTime
     * @covers ::getSeqTime
     */
    public function it_accepts_a_datetime_as_sequence_time()
    {
        $accountEntryExtensionV3 = (new AccountEntryExtensionV3())
            ->withSeqTime(new DateTime());

        $this->assertInstanceOf(TimePoint::class, $accountEntryExtensionV3->getSeqTime());
    }

    /**
     * @test
     * @covers ::withSeqTime
     * @covers ::getSeqTime
     */
    public function it_accepts_a_time_point_as_sequence_time()
    {
        $accountEntryExtensionV3 = (new AccountEntryExtensionV3())
            ->withSeqTime(TimePoint::fromUnixEpoch(1641024000));

        $this->assertInstanceOf(TimePoint::class, $accountEntryExtensionV3->getSeqTime());
    }
}
