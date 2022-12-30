<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\GeneralizedTransactionSet;
use StageRightLabs\Bloom\Transaction\TransactionSetV1;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\GeneralizedTransactionSet
 */
class GeneralizedTransactionSetTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, GeneralizedTransactionSet::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            1 => TransactionSetV1::class,
        ];

        $this->assertEquals($expected, GeneralizedTransactionSet::arms());
    }

    /**
     * @test
     * @covers ::wrapTransactionSetV1
     * @covers ::unwrap
     */
    public function it_can_wrap_a_transaction_set_v1()
    {
        $generalizedTransactionSet = GeneralizedTransactionSet::wrapTransactionSetV1(new TransactionSetV1());

        $this->assertInstanceOf(GeneralizedTransactionSet::class, $generalizedTransactionSet);
        $this->assertInstanceOf(TransactionSetV1::class, $generalizedTransactionSet->unwrap());
    }
}
