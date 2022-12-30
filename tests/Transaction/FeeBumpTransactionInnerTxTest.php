<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Envelope\EnvelopeType;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\FeeBumpTransactionInnerTx;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\FeeBumpTransactionInnerTx
 */
class FeeBumpTransactionInnerTxTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(EnvelopeType::class, FeeBumpTransactionInnerTx::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            EnvelopeType::ENVELOPE_TRANSACTION => TransactionV1Envelope::class,
        ];

        $this->assertEquals($expected, FeeBumpTransactionInnerTx::arms());
    }

    /**
     * @test
     * @covers ::wrapTransactionV1Envelope
     */
    public function it_can_be_instantiated_from_a_transaction_v1_envelope()
    {
        $envelope = new TransactionV1Envelope();
        $inner = FeeBumpTransactionInnerTx::wrapTransactionV1Envelope($envelope);

        $this->assertInstanceOf(FeeBumpTransactionInnerTx::class, $inner);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_can_be_unwrapped()
    {
        $envelope = new TransactionV1Envelope();
        $inner = FeeBumpTransactionInnerTx::wrapTransactionV1Envelope($envelope);

        $this->assertInstanceOf(TransactionV1Envelope::class, $inner->unwrap());
    }
}
