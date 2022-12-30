<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Cryptography;

use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\DecoratedSignatureList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\DecoratedSignatureList
 */
class DecoratedSignatureListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(DecoratedSignature::class, DecoratedSignatureList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_limits_the_number_of_total_operations_as_defined_in_the_spec()
    {
        $this->assertEquals(DecoratedSignatureList::MAX_SIGNATURE_COUNT, DecoratedSignatureList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $this->assertEmpty(DecoratedSignatureList::empty());
    }
}
