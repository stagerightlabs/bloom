<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TxSetComponent;
use StageRightLabs\Bloom\Transaction\TxSetComponentTxsMaybeDiscountedFee;
use StageRightLabs\Bloom\Transaction\TxSetComponentType;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TxSetComponent
 */
class TxSetComponentTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(TxSetComponentType::class, TxSetComponent::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            TxSetComponentType::TXSET_COMP_TXS_MAYBE_DISCOUNTED_FEE => TxSetComponentTxsMaybeDiscountedFee::class,
        ];

        $this->assertEquals($expected, TxSetComponent::arms());
    }

    /**
     * @test
     * @covers ::wrapTxSetComponentTxsMaybeDiscountedFee
     * @covers ::unwrap
     */
    public function it_can_wrap_a_tx_set_component_txs_maybe_discounted_fee()
    {
        $txSetComponent = TxSetComponent::wrapTxSetComponentTxsMaybeDiscountedFee(
            new TxSetComponentTxsMaybeDiscountedFee()
        );

        $this->assertInstanceOf(TxSetComponent::class, $txSetComponent);
        $this->assertInstanceOf(TxSetComponentTxsMaybeDiscountedFee::class, $txSetComponent->unwrap());
    }
}
