<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\BucketEntryType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\BucketEntryType
 */
class BucketEntryTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            -1 => BucketEntryType::META_ENTRY,
            0  => BucketEntryType::LIVE_ENTRY,
            1  => BucketEntryType::DEAD_ENTRY,
            2  => BucketEntryType::INIT_ENTRY,
        ];
        $bucketEntryType = new BucketEntryType();

        $this->assertEquals($expected, $bucketEntryType->getOptions());
    }

    /**
     * @test
     * @covers ::meta
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_meta_type()
    {
        $bucketEntryType = BucketEntryType::meta();

        $this->assertInstanceOf(BucketEntryType::class, $bucketEntryType);
        $this->assertEquals(BucketEntryType::META_ENTRY, $bucketEntryType->getType());
    }

    /**
     * @test
     * @covers ::live
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_live_type()
    {
        $bucketEntryType = BucketEntryType::live();

        $this->assertInstanceOf(BucketEntryType::class, $bucketEntryType);
        $this->assertEquals(BucketEntryType::LIVE_ENTRY, $bucketEntryType->getType());
    }

    /**
     * @test
     * @covers ::dead
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_dead_type()
    {
        $bucketEntryType = BucketEntryType::dead();

        $this->assertInstanceOf(BucketEntryType::class, $bucketEntryType);
        $this->assertEquals(BucketEntryType::DEAD_ENTRY, $bucketEntryType->getType());
    }

    /**
     * @test
     * @covers ::init
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_init_type()
    {
        $bucketEntryType = BucketEntryType::init();

        $this->assertInstanceOf(BucketEntryType::class, $bucketEntryType);
        $this->assertEquals(BucketEntryType::INIT_ENTRY, $bucketEntryType->getType());
    }
}
