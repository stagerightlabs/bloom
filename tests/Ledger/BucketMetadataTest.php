<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\BucketMetadata;
use StageRightLabs\Bloom\Ledger\BucketMetadataExt;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\BucketMetadata
 */
class BucketMetadataTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $bucketMetadata = (new BucketMetadata())->withLedgerVersion(UInt32::of(1));
        $buffer = XDR::fresh()->write($bucketMetadata);

        $this->assertEquals('AAAAAQAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_version_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new BucketMetadata());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $bucketMetadata = XDR::fromBase64('AAAAAQAAAAA=')->read(BucketMetadata::class);

        $this->assertInstanceOf(BucketMetadata::class, $bucketMetadata);
        $this->assertInstanceOf(UInt32::class, $bucketMetadata->getLedgerVersion());
        $this->assertInstanceOf(BucketMetadataExt::class, $bucketMetadata->getExtension());
    }

    /**
     * @test
     * @covers ::withLedgerVersion
     * @covers ::getLedgerVersion
     */
    public function it_accepts_a_uint32_as_a_ledger_version()
    {
        $bucketMetadata = (new BucketMetadata())->withLedgerVersion(UInt32::of(1));

        $this->assertInstanceOf(BucketMetadata::class, $bucketMetadata);
        $this->assertInstanceOf(UInt32::class, $bucketMetadata->getLedgerVersion());
    }

    /**
     * @test
     * @covers ::withLedgerVersion
     * @covers ::getLedgerVersion
     */
    public function it_accepts_a_native_int_as_a_ledger_version()
    {
        $bucketMetadata = (new BucketMetadata())->withLedgerVersion(1);

        $this->assertInstanceOf(BucketMetadata::class, $bucketMetadata);
        $this->assertInstanceOf(UInt32::class, $bucketMetadata->getLedgerVersion());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $bucketMetadata = (new BucketMetadata())
            ->withExtension(BucketMetadataExt::empty());

        $this->assertInstanceOf(BucketMetadata::class, $bucketMetadata);
        $this->assertInstanceOf(BucketMetadataExt::class, $bucketMetadata->getExtension());
    }
}
