<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Envelope\EnvelopeType;
use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\FeeBumpTransaction;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayloadTaggedTransaction;
use StageRightLabs\Bloom\Transaction\TransactionV0;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionSignaturePayloadTaggedTransaction
 */
class TransactionSignaturePayloadTaggedTransactionTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(EnvelopeType::class, TransactionSignaturePayloadTaggedTransaction::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            EnvelopeType::ENVELOPE_TRANSACTION => Transaction::class,
            EnvelopeType::ENVELOPE_FEE_BUMP    => FeeBumpTransaction::class,
        ];

        $this->assertEquals($expected, TransactionSignaturePayloadTaggedTransaction::arms());
    }

    /**
     * @test
     * @covers ::wrapTransaction
     */
    public function it_can_be_created_from_a_v0_transaction()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transactionV0 = TransactionV0::for($account, $sequenceNumber);
        $taggedTransaction = TransactionSignaturePayloadTaggedTransaction::wrapTransaction($transactionV0);

        $this->assertInstanceOf(TransactionSignaturePayloadTaggedTransaction::class, $taggedTransaction);
        $this->assertInstanceOf(Transaction::class, $taggedTransaction->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTransaction
     * @covers ::unwrap
     */
    public function it_can_be_created_from_a_v1_transaction()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(
            Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
        );
        $taggedTransaction = TransactionSignaturePayloadTaggedTransaction::wrapTransaction($transaction);

        $this->assertInstanceOf(TransactionSignaturePayloadTaggedTransaction::class, $taggedTransaction);
        $this->assertInstanceOf(Transaction::class, $taggedTransaction->unwrap());
    }

    /**
     * @test
     * @covers ::wrapFeeBumpTransaction
     */
    public function it_can_be_created_from_a_fee_bump_transaction()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            startingBalance: '10',
            source: $account,
        );
        $transaction = $transaction->withOperation($operation);
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope(
            (new TransactionV1Envelope())->withTransaction($transaction)
        );
        $feeBumpTransaction = FeeBumpTransaction::for($envelope, Int64::of(200), $transaction->getSourceAccount());

        $taggedTransaction = TransactionSignaturePayloadTaggedTransaction::wrapFeeBumpTransaction($feeBumpTransaction);

        $this->assertInstanceOf(TransactionSignaturePayloadTaggedTransaction::class, $taggedTransaction);
        $this->assertInstanceOf(FeeBumpTransaction::class, $taggedTransaction->unwrap());
    }
}
