<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Cryptography\ExtraSigners;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\ExtraSigners
 */
class ExtraSignersTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(SignerKey::class, ExtraSigners::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_limits_the_number_of_total_operations_as_defined_in_the_spec()
    {
        $this->assertEquals(ExtraSigners::MAX_SIGNATURE_COUNT, ExtraSigners::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $this->assertEmpty(ExtraSigners::empty());
    }
}
