<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Ledger\SkipList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\SkipList
 */
class SkipListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(Hash::class, SkipList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     * @covers ::getXdrLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(SkipList::MAX_LENGTH, SkipList::getMaxLength());
        $this->assertEquals(SkipList::MAX_LENGTH, SkipList::getXdrLength());
    }
}
