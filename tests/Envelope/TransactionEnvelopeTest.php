<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Envelope;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\DecoratedSignatureList;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Envelope\EnvelopeType;
use StageRightLabs\Bloom\Envelope\FeeBumpTransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionV0Envelope;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\FeeBumpTransaction;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionV0;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Envelope\TransactionEnvelope
 */
class TransactionEnvelopeTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(EnvelopeType::class, TransactionEnvelope::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            EnvelopeType::ENVELOPE_TRANSACTION_V0 => TransactionV0Envelope::class,
            EnvelopeType::ENVELOPE_TRANSACTION    => TransactionV1Envelope::class,
            EnvelopeType::ENVELOPE_FEE_BUMP       => FeeBumpTransactionEnvelope::class,
        ];

        $this->assertEquals($expected, TransactionEnvelope::arms());
    }

    /**
     * @test
     * @covers ::wrapTransactionV0Envelope
     */
    public function it_can_be_created_from_a_v0_transaction()
    {
        $envelope = TransactionEnvelope::wrapTransactionV0Envelope(new TransactionV0Envelope());
        $this->assertInstanceOf(TransactionEnvelope::class, $envelope);
        $this->assertInstanceOf(TransactionV0Envelope::class, $envelope->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTransactionV1Envelope
     */
    public function it_can_be_created_from_a_v1_transaction()
    {
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope(new TransactionV1Envelope());
        $this->assertInstanceOf(TransactionEnvelope::class, $envelope);
        $this->assertInstanceOf(TransactionV1Envelope::class, $envelope->unwrap());
    }

    /**
     * @test
     * @covers ::wrapFeeBumpTransactionEnvelope
     */
    public function it_can_be_created_from_a_fee_bump_transaction()
    {
        $envelope = TransactionEnvelope::wrapFeeBumpTransactionEnvelope(new FeeBumpTransactionEnvelope());
        $this->assertInstanceOf(TransactionEnvelope::class, $envelope);
        $this->assertInstanceOf(FeeBumpTransactionEnvelope::class, $envelope->unwrap());
    }

    /**
     * @test
     * @covers ::enclose
     */
    public function it_encloses_a_transaction()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);

        $envelope = TransactionEnvelope::enclose($transaction);

        $this->assertInstanceOf(TransactionEnvelope::class, $envelope);
    }

    /**
     * @test
     * @covers ::enclose
     */
    public function it_wraps_a_transaction_v0()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = TransactionV0::for($account, $sequenceNumber);

        $envelope = TransactionEnvelope::enclose($transaction);

        $this->assertInstanceOf(TransactionEnvelope::class, $envelope);
    }

    /**
     * @test
     * @covers ::enclose
     */
    public function it_wraps_a_fee_bump_transaction()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $tx1env = TransactionEnvelope::wrapTransactionV1Envelope(
            (new TransactionV1Envelope())->withTransaction($transaction)
        );
        $feeBumpTransaction = FeeBumpTransaction::for(
            $tx1env,
            Int64::of(100),
            $transaction->getSourceAccount()
        );

        $envelope = TransactionEnvelope::enclose($feeBumpTransaction);

        $this->assertInstanceOf(TransactionEnvelope::class, $envelope);
    }

    /**
     * @test
     * @covers ::getSignatures
     */
    public function it_returns_the_underlying_signature_list()
    {
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $txv1env = (new TransactionV1Envelope())
            ->withSignatures($signatureList);
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope($txv1env);

        $this->assertInstanceOf(DecoratedSignatureList::class, $envelope->getSignatures());
    }

    /**
     * @test
     * @covers ::getSignatures
     */
    public function it_returns_an_empty_signature_list_if_no_signatures_are_available()
    {
        $signatureList = (new TransactionEnvelope())->getSignatures();

        $this->assertInstanceOf(DecoratedSignatureList::class, $signatureList);
        $this->assertEmpty($signatureList);
    }

    /**
     * @test
     * @covers ::addSignature
     */
    public function it_accepts_additional_signatures_for_transaction_v1_envelopes()
    {
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::empty();
        $txV1Env = (new TransactionV1Envelope())
            ->withSignatures($signatureList);

        $envelope = TransactionEnvelope::wrapTransactionV1Envelope($txV1Env);
        $this->assertCount(0, $envelope->getSignatures());

        $envelope = $envelope->addSignature($signature);
        $this->assertCount(1, $envelope->getSignatures());
    }

    /**
     * @test
     * @covers ::addSignature
     */
    public function it_accepts_additional_signatures_for_transaction_v0_envelopes()
    {
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::empty();
        $txV0Env = (new TransactionV0Envelope())
            ->withSignatures($signatureList);

        $envelope = TransactionEnvelope::wrapTransactionV0Envelope($txV0Env);
        $this->assertCount(0, $envelope->getSignatures());

        $envelope = $envelope->addSignature($signature);
        $this->assertCount(1, $envelope->getSignatures());
    }

    /**
     * @test
     * @covers ::addSignature
     */
    public function it_accepts_additional_signatures_for_fee_bump_transaction_envelopes()
    {
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::empty();
        $feeBumpEnv = (new FeeBumpTransactionEnvelope())
            ->withSignatures($signatureList);

        $envelope = TransactionEnvelope::wrapFeeBumpTransactionEnvelope($feeBumpEnv);
        $this->assertCount(0, $envelope->getSignatures());

        $envelope = $envelope->addSignature($signature);
        $this->assertCount(1, $envelope->getSignatures());
    }

    /**
     * @test
     * @covers ::addSignature
     */
    public function it_does_not_accept_signatures_for_invalid_transaction_envelopes()
    {
        $this->expectException(InvalidArgumentException::class);
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        (new TransactionEnvelope())->addSignature($signature);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_can_be_unwrapped()
    {
        $txV1Env = new TransactionV1Envelope();
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope($txV1Env);

        $this->assertInstanceOf(TransactionV1Envelope::class, $envelope->unwrap());
    }

    /**
     * @test
     * @covers ::getTransaction
     */
    public function it_returns_the_underlying_transaction()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(
            Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
        );
        $txv1env = (new TransactionV1Envelope())
            ->withTransaction($transaction);
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope($txv1env);

        $this->assertInstanceOf(Transaction::class, $envelope->getTransaction());
    }

    /**
     * @test
     * @covers ::getTransaction
     */
    public function it_returns_a_null_transaction_if_it_does_not_have_a_valid_transaction_envelope()
    {
        $this->assertNull((new TransactionEnvelope())->getTransaction());
    }

    /**
     * @test
     * @covers ::getHash
     */
    public function it_returns_the_underlying_signature_payload_hash()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(
            Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
        );
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $txv1env = (new TransactionV1Envelope())
            ->withTransaction($transaction)
            ->withSignatures($signatureList);
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope($txv1env);

        $hash = $envelope->getHash(Bloom::TEST_NETWORK_PASSPHRASE);

        $this->assertInstanceOf(Hash::class, $hash);
        $this->assertEquals('e8bd5964ca5062058e27f4194f07adeb4b5f6dc54ac5bce2c57596673971b962', $hash->toHex());
    }

    /**
     * @test
     * @covers ::getHash
     */
    public function it_returns_a_null_hash_if_it_does_not_have_a_valid_transaction_envelope()
    {
        $this->assertNull((new TransactionEnvelope())->getHash(Bloom::TEST_NETWORK_PASSPHRASE));
    }
}
