<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Account\SignerList;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\SignerList
 */
class SignerListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(Signer::class, SignerList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(Bloom::MAX_SIGNERS, SignerList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $signerList = SignerList::empty();

        $this->assertInstanceOf(SignerList::class, $signerList);
        $this->assertEmpty($signerList);
    }
}
