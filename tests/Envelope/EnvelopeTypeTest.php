<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Envelope;

use StageRightLabs\Bloom\Envelope\EnvelopeType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Envelope\EnvelopeType
 */
class EnvelopeTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => EnvelopeType::ENVELOPE_TRANSACTION_V0,
            1 => EnvelopeType::ENVELOPE_SCP,
            2 => EnvelopeType::ENVELOPE_TRANSACTION,
            3 => EnvelopeType::ENVELOPE_AUTH,
            4 => EnvelopeType::ENVELOPE_SCP_VALUE,
            5 => EnvelopeType::ENVELOPE_FEE_BUMP,
            6 => EnvelopeType::ENVELOPE_OP_ID,
            7 => EnvelopeType::ENVELOPE_POOL_REVOKE_OP_ID,
        ];
        $envelopeType = new EnvelopeType();

        $this->assertEquals($expected, $envelopeType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $envelopeType = EnvelopeType::transactionV0();
        $this->assertEquals(EnvelopeType::ENVELOPE_TRANSACTION_V0, $envelopeType->getType());
    }

    /**
     * @test
     * @covers ::transactionV0
     */
    public function it_can_be_instantiated_as_a_transaction_v0_type()
    {
        $envelopeType = EnvelopeType::transactionV0();

        $this->assertInstanceOf(EnvelopeType::class, $envelopeType);
        $this->assertEquals(EnvelopeType::ENVELOPE_TRANSACTION_V0, $envelopeType->getType());
    }

    /**
     * @test
     * @covers ::scp
     */
    public function it_can_be_instantiated_as_a_scp_type()
    {
        $envelopeType = EnvelopeType::scp();

        $this->assertInstanceOf(EnvelopeType::class, $envelopeType);
        $this->assertEquals(EnvelopeType::ENVELOPE_SCP, $envelopeType->getType());
    }

    /**
     * @test
     * @covers ::transaction
     */
    public function it_can_be_instantiated_as_a_transaction_type()
    {
        $envelopeType = EnvelopeType::transaction();

        $this->assertInstanceOf(EnvelopeType::class, $envelopeType);
        $this->assertEquals(EnvelopeType::ENVELOPE_TRANSACTION, $envelopeType->getType());
    }

    /**
     * @test
     * @covers ::auth
     */
    public function it_can_be_instantiated_as_an_auth_type()
    {
        $envelopeType = EnvelopeType::auth();

        $this->assertInstanceOf(EnvelopeType::class, $envelopeType);
        $this->assertEquals(EnvelopeType::ENVELOPE_AUTH, $envelopeType->getType());
    }

    /**
     * @test
     * @covers ::scpValue
     */
    public function it_can_be_instantiated_as_a_scp_value_type()
    {
        $envelopeType = EnvelopeType::scpValue();

        $this->assertInstanceOf(EnvelopeType::class, $envelopeType);
        $this->assertEquals(EnvelopeType::ENVELOPE_SCP_VALUE, $envelopeType->getType());
    }

    /**
     * @test
     * @covers ::feeBump
     */
    public function it_can_be_instantiated_as_a_fee_bump_type()
    {
        $envelopeType = EnvelopeType::feeBump();

        $this->assertInstanceOf(EnvelopeType::class, $envelopeType);
        $this->assertEquals(EnvelopeType::ENVELOPE_FEE_BUMP, $envelopeType->getType());
    }

    /**
     * @test
     * @covers ::operationId
     */
    public function it_can_be_instantiated_as_an_operation_id_type()
    {
        $envelopeType = EnvelopeType::operationId();

        $this->assertInstanceOf(EnvelopeType::class, $envelopeType);
        $this->assertEquals(EnvelopeType::ENVELOPE_OP_ID, $envelopeType->getType());
    }

    /**
     * @test
     * @covers ::poolRevokeOperationId
     */
    public function it_can_be_instantiated_as_a_pool_revoke_operation_id_type()
    {
        $envelopeType = EnvelopeType::poolRevokeOperationId();

        $this->assertInstanceOf(EnvelopeType::class, $envelopeType);
        $this->assertEquals(EnvelopeType::ENVELOPE_POOL_REVOKE_OP_ID, $envelopeType->getType());
    }
}
