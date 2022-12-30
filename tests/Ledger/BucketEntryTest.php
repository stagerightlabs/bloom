<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Ledger\BucketEntry;
use StageRightLabs\Bloom\Ledger\BucketEntryType;
use StageRightLabs\Bloom\Ledger\BucketMetadata;
use StageRightLabs\Bloom\Ledger\DataEntry;
use StageRightLabs\Bloom\Ledger\LedgerEntry;
use StageRightLabs\Bloom\Ledger\LedgerEntryData;
use StageRightLabs\Bloom\Ledger\LedgerKey;
use StageRightLabs\Bloom\Ledger\LedgerKeyData;
use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\BucketEntry
 */
class BucketEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(BucketEntryType::class, BucketEntry::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            BucketEntryType::LIVE_ENTRY => LedgerEntry::class,
            BucketEntryType::INIT_ENTRY => LedgerEntry::class,
            BucketEntryType::DEAD_ENTRY => LedgerKey::class,
            BucketEntryType::META_ENTRY => BucketMetadata::class,
        ];

        $this->assertEquals($expected, BucketEntry::arms());
    }

    /**
     * @test
     * @covers ::wrapLiveLedgerEntry
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_live_ledger_entry()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('name'))
            ->withDataValue(DataValue::of('value'));
        $ledgerEntryData = LedgerEntryData::wrapDataEntry($dataEntry);
        $ledgerEntry = (new LedgerEntry())
            ->withLastModifiedLedgerSeq(UInt32::of(1))
            ->withData($ledgerEntryData);
        $bucketEntry = BucketEntry::wrapLiveLedgerEntry($ledgerEntry);

        $this->assertInstanceOf(BucketEntry::class, $bucketEntry);
        $this->assertInstanceOf(LedgerEntry::class, $bucketEntry->unwrap());
        $this->assertEquals(BucketEntryType::LIVE_ENTRY, $bucketEntry->getType());
    }

    /**
     * @test
     * @covers ::wrapInitLedgerEntry
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_init_ledger_entry()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('name'))
            ->withDataValue(DataValue::of('value'));
        $ledgerEntryData = LedgerEntryData::wrapDataEntry($dataEntry);
        $ledgerEntry = (new LedgerEntry())
            ->withLastModifiedLedgerSeq(UInt32::of(1))
            ->withData($ledgerEntryData);
        $bucketEntry = BucketEntry::wrapInitLedgerEntry($ledgerEntry);

        $this->assertInstanceOf(BucketEntry::class, $bucketEntry);
        $this->assertInstanceOf(LedgerEntry::class, $bucketEntry->unwrap());
        $this->assertEquals(BucketEntryType::INIT_ENTRY, $bucketEntry->getType());
    }

    /**
     * @test
     * @covers ::wrapDeadLedgerKey
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_ledger_key()
    {
        $ledgerKeyData = (new LedgerKeyData())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('example'));
        $ledgerKey = LedgerKey::wrapLedgerKeyData($ledgerKeyData);
        $bucketEntry = BucketEntry::wrapDeadLedgerKey($ledgerKey);

        $this->assertInstanceOf(BucketEntry::class, $bucketEntry);
        $this->assertInstanceOf(LedgerKey::class, $bucketEntry->unwrap());
        $this->assertEquals(BucketEntryType::DEAD_ENTRY, $bucketEntry->getType());
    }

    /**
     * @test
     * @covers ::wrapBucketMetadata
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_bucket_metadata()
    {
        $bucketMetadata = (new BucketMetadata())->withLedgerVersion(UInt32::of(1));
        $bucketEntry = BucketEntry::wrapBucketMetadata($bucketMetadata);

        $this->assertInstanceOf(BucketEntry::class, $bucketEntry);
        $this->assertInstanceOf(BucketMetadata::class, $bucketEntry->unwrap());
        $this->assertEquals(BucketEntryType::META_ENTRY, $bucketEntry->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_a_null_type_when_no_value_is_set()
    {
        $this->assertNull((new BucketEntry())->getType());
    }
}
