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
use StageRightLabs\Bloom\Envelope\FeeBumpTransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\FeeBumpTransaction;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayload;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayloadTaggedTransaction;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Envelope\FeeBumpTransactionEnvelope
 */
class FeeBumpTransactionEnvelopeTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
            source: $account,
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31')));
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $transactionEnvelope = TransactionEnvelope::wrapTransactionV1Envelope(
            (new TransactionV1Envelope())
                ->withTransaction($transaction)
                ->withSignatures($signatureList)
        );
        $feeBumpTransaction = FeeBumpTransaction::for($transactionEnvelope, Int64::of(200), $transaction->getSourceAccount());
        $feeBumpEnvelope = (new FeeBumpTransactionEnvelope())
            ->withTransaction($feeBumpTransaction)
            ->withSignatures($signatureList);
        $buffer = XDR::fresh()->write($feeBumpEnvelope);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAMgAAAACAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAZAAAAAAAAAABAAAAAQAAAABf7mYAAAAAAGHOSAAAAAAAAAAAAQAAAAEAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAAAAAABVDUP46pOTFitIqVaR9Pl5fRwJPIbIkZxU2X9svKzmLAAAAAAX14QAAAAAAAAAAAWhpbnQAAAAJc2lnbmF0dXJlAAAAAAAAAAAAAAFoaW50AAAACXNpZ25hdHVyZQAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_transaction_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $feeBumpEnvelope = (new FeeBumpTransactionEnvelope())
            ->withSignatures($signatureList);
        XDR::fresh()->write($feeBumpEnvelope);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_signature_list_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
            source: $account,
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31')));
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $transactionEnvelope = TransactionEnvelope::wrapTransactionV1Envelope(
            (new TransactionV1Envelope())
                ->withTransaction($transaction)
                ->withSignatures($signatureList)
        );
        $feeBumpTransaction = FeeBumpTransaction::for($transactionEnvelope, Int64::of(200), $transaction->getSourceAccount());
        $feeBumpEnvelope = (new FeeBumpTransactionEnvelope())
            ->withTransaction($feeBumpTransaction);
        XDR::fresh()->write($feeBumpEnvelope);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr_check()
    {
        $feeBumpEnvelope = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAMgAAAACAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAZAAAAAAAAAABAAAAAQAAAABf7mYAAAAAAGHOSAAAAAAAAAAAAQAAAAEAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAAAAAABVDUP46pOTFitIqVaR9Pl5fRwJPIbIkZxU2X9svKzmLAAAAAAX14QAAAAAAAAAAAWhpbnQAAAAJc2lnbmF0dXJlAAAAAAAAAAAAAAFoaW50AAAACXNpZ25hdHVyZQAAAA==')
            ->read(FeeBumpTransactionEnvelope::class);

        $this->assertInstanceOf(FeeBumpTransactionEnvelope::class, $feeBumpEnvelope);
        $this->assertInstanceOf(FeeBumpTransaction::class, $feeBumpEnvelope->getTransaction());
        $this->assertInstanceOf(TransactionV1Envelope::class, $feeBumpEnvelope->getTransaction()->getInnerTransaction()->unwrap());
        $this->assertTrue($feeBumpEnvelope->getTransaction()->getFee()->isEqualTo(200));
    }

    /**
     * @test
     * @covers ::getTransaction
     * @covers ::withTransaction
     */
    public function it_accepts_a_fee_bump_transaction()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
            source: $account,
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31')));
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $transactionEnvelope = TransactionEnvelope::wrapTransactionV1Envelope(
            (new TransactionV1Envelope())
                ->withTransaction($transaction)
                ->withSignatures($signatureList)
        );
        $feeBumpTransaction = FeeBumpTransaction::for($transactionEnvelope, Int64::of(200), $transaction->getSourceAccount());
        $feeBumpEnvelope = (new FeeBumpTransactionEnvelope())->withTransaction($feeBumpTransaction);

        $this->assertInstanceOf(FeeBumpTransactionEnvelope::class, $feeBumpEnvelope);
        $this->assertInstanceOf(FeeBumpTransaction::class, $feeBumpEnvelope->getTransaction());
        $this->assertInstanceOf(TransactionV1Envelope::class, $feeBumpEnvelope->getTransaction()->getInnerTransaction()->unwrap());
        $this->assertTrue($feeBumpEnvelope->getTransaction()->getFee()->isEqualTo(200));
    }

    /**
     * @test
     * @covers ::getSignatures
     * @covers ::withSignatures
     */
    public function it_accepts_a_signature_list()
    {
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $feeBumpEnvelope = (new FeeBumpTransactionEnvelope())->withSignatures($signatureList);

        $this->assertInstanceOf(FeeBumpTransactionEnvelope::class, $feeBumpEnvelope);
        $this->assertInstanceOf(DecoratedSignatureList::class, $feeBumpEnvelope->getSignatures());
    }

    /**
     * @test
     * @covers ::addSignature
     */
    public function it_accepts_additional_signatures()
    {
        $signatureList = DecoratedSignatureList::empty();
        $feeBumpEnvelope = (new FeeBumpTransactionEnvelope())->withSignatures($signatureList);
        $this->assertCount(0, $feeBumpEnvelope->getSignatures());

        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $feeBumpEnvelope = $feeBumpEnvelope->addSignature($signature);
        $this->assertCount(1, $feeBumpEnvelope->getSignatures());
    }

    /**
     * @test
     * @covers ::withEmptySignatureList
     */
    public function it_initializes_an_empty_signature_list()
    {
        $envelope = (new FeeBumpTransactionEnvelope())->withEmptySignatureList();

        $this->assertInstanceOf(DecoratedSignatureList::class, $envelope->getSignatures());
        $this->assertEquals(0, $envelope->getSignatures()->count());
    }

    /**
     * @test
     * @covers ::getSignaturePayload
     */
    public function it_returns_a_signature_payload()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
            source: $account,
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31')));
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $transactionEnvelope = TransactionEnvelope::wrapTransactionV1Envelope(
            (new TransactionV1Envelope())
                ->withTransaction($transaction)
                ->withSignatures($signatureList)
        );
        $feeBumpTransaction = FeeBumpTransaction::for($transactionEnvelope, Int64::of(200), $transaction->getSourceAccount());
        $feeBumpEnvelope = (new FeeBumpTransactionEnvelope())
            ->withTransaction($feeBumpTransaction)
            ->withSignatures($signatureList);

        $signaturePayload = $feeBumpEnvelope->getSignaturePayload(Bloom::TEST_NETWORK_PASSPHRASE);

        $this->assertInstanceOf(TransactionSignaturePayload::class, $signaturePayload);
        $this->assertInstanceOf(TransactionSignaturePayloadTaggedTransaction::class, $signaturePayload->getTaggedTransaction());
    }

    /**
     * @test
     * @covers ::getHash
     */
    public function it_returns_a_signature_payload_hash()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
            source: $account,
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31')));
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $transactionEnvelope = TransactionEnvelope::wrapTransactionV1Envelope(
            (new TransactionV1Envelope())
                ->withTransaction($transaction)
                ->withSignatures($signatureList)
        );
        $feeBumpTransaction = FeeBumpTransaction::for($transactionEnvelope, Int64::of(200), $transaction->getSourceAccount());
        $feeBumpEnvelope = (new FeeBumpTransactionEnvelope())
            ->withTransaction($feeBumpTransaction)
            ->withSignatures($signatureList);

        $hash = $feeBumpEnvelope->getHash(Bloom::TEST_NETWORK_PASSPHRASE);

        $this->assertInstanceOf(Hash::class, $hash);
        $this->assertEquals('f47304ecf0e7727a7094c7014f43800d410ac7672c0834449770a1b5be19b0d0', $hash->toHex());
    }
}
