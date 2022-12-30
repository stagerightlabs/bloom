<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\DecoratedSignatureList;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionV0Envelope;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\FeeBumpTransaction;
use StageRightLabs\Bloom\Transaction\FeeBumpTransactionExt;
use StageRightLabs\Bloom\Transaction\FeeBumpTransactionInnerTx;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionV0;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\FeeBumpTransaction
 */
class FeeBumpTransactionTest extends TestCase
{
    /**
     * @test
     * @covers ::for
     */
    public function it_can_be_instantiated_from_a_transaction_envelope()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
        );
        $transaction = $transaction->withOperation($operation);
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope(
            (new TransactionV1Envelope())->withTransaction($transaction)
        );
        $feeBumpTransaction = FeeBumpTransaction::for($envelope, Int64::of(200), $transaction->getSourceAccount());

        $this->assertInstanceOf(FeeBumpTransaction::class, $feeBumpTransaction);
        $this->assertTrue($feeBumpTransaction->getFee()->isEqualTo(200));
    }

    /**
     * @test
     * @covers ::for
     */
    public function it_will_only_accept_v1_transactions()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = TransactionV0::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
        );
        $transaction = $transaction->withOperation($operation);
        $envelope = TransactionEnvelope::wrapTransactionV0Envelope(
            (new TransactionV0Envelope())->withTransaction($transaction)
        );
        FeeBumpTransaction::for($envelope, Int64::of(200), 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
    }

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
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(
                Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
            );
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope(
            (new TransactionV1Envelope())
                ->withTransaction($transaction)
                ->withSignatures($signatureList)
        );
        $feeBumpTransaction = FeeBumpTransaction::for($envelope, Int64::of(200), $transaction->getSourceAccount());
        $buffer = XDR::fresh()->write($feeBumpTransaction);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAMgAAAACAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAZAAAAAAAAAABAAAAAQAAAABf7mYAAAAAAGHOSAAAAAAAAAAAAQAAAAAAAAAAAAAAABVDUP46pOTFitIqVaR9Pl5fRwJPIbIkZxU2X9svKzmLAAAAAAX14QAAAAAAAAAAAWhpbnQAAAAJc2lnbmF0dXJlAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_fee_source_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(
                Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
            );
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $envelope = (new TransactionV1Envelope())
            ->withTransaction($transaction)
            ->withSignatures($signatureList);
        $innerTransaction = FeeBumpTransactionInnerTx::wrapTransactionV1Envelope($envelope);
        $feeBumpTransaction = (new FeeBumpTransaction())
            ->withInnerTransaction($innerTransaction)
            ->withFee(Int64::of(200));
        XDR::fresh()->write($feeBumpTransaction);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_fee_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(
                Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
            );
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $envelope = (new TransactionV1Envelope())
            ->withTransaction($transaction)
            ->withSignatures($signatureList);
        $innerTransaction = FeeBumpTransactionInnerTx::wrapTransactionV1Envelope($envelope);
        $feeBumpTransaction = (new FeeBumpTransaction())
            ->withInnerTransaction($innerTransaction)
            ->withFeeSource($transaction->getSourceAccount());
        XDR::fresh()->write($feeBumpTransaction);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_inner_transaction_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(
                Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
            );
        $feeBumpTransaction = (new FeeBumpTransaction())
            ->withFee(Int64::of(200))
            ->withFeeSource($transaction->getSourceAccount());
        XDR::fresh()->write($feeBumpTransaction);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_ext_will_be_generated_automatically_upon_xdr_conversion()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
        );
        $transaction = $transaction
            ->withOperation($operation)
            ->withPreconditions(
                Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
            );
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $envelope = (new TransactionV1Envelope())
            ->withTransaction($transaction)
            ->withSignatures($signatureList);
        $innerTransaction = FeeBumpTransactionInnerTx::wrapTransactionV1Envelope($envelope);
        $feeBumpTransaction = (new FeeBumpTransaction())
            ->withInnerTransaction($innerTransaction)
            ->withFeeSource($transaction->getSourceAccount())
            ->withFee(Int64::of(200));
        $buffer = XDR::fresh()->write($feeBumpTransaction);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAMgAAAACAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAZAAAAAAAAAABAAAAAQAAAABf7mYAAAAAAGHOSAAAAAAAAAAAAQAAAAAAAAAAAAAAABVDUP46pOTFitIqVaR9Pl5fRwJPIbIkZxU2X9svKzmLAAAAAAX14QAAAAAAAAAAAWhpbnQAAAAJc2lnbmF0dXJlAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $feeBumpTransaction = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAMgAAAACAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAZAAAAAAAAAABAAAAAQAAAABf7mYAAAAAAGHOSAAAAAAAAAAAAQAAAAEAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAAAAAABVDUP46pOTFitIqVaR9Pl5fRwJPIbIkZxU2X9svKzmLAAAAAAX14QAAAAAAAAAAAWhpbnQAAAAJc2lnbmF0dXJlAAAAAAAAAA==')
            ->read(FeeBumpTransaction::class);

        $this->assertInstanceOf(FeeBumpTransaction::class, $feeBumpTransaction);
        $this->assertInstanceOf(TransactionV1Envelope::class, $feeBumpTransaction->getInnerTransaction()->unwrap());
        $this->assertTrue($feeBumpTransaction->getFee()->isEqualTo(200));
    }

    /**
     * @test
     * @covers ::getFeeSource
     * @covers ::withFeeSource
     */
    public function it_accepts_a_fee_source()
    {
        $account = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = (new FeeBumpTransaction())->withFeeSource($account);

        $this->assertInstanceOf(FeeBumpTransaction::class, $transaction);
        $this->assertInstanceOf(AccountId::class, $transaction->getFeeSource());
    }

    /**
     * @test
     * @covers ::getFee
     * @covers ::withFee
     */
    public function it_accepts_an_int64_fee()
    {
        $fee = Int64::of(100);
        $transaction = (new FeeBumpTransaction())->withFee($fee);

        $this->assertInstanceOf(FeeBumpTransaction::class, $transaction);
        $this->assertInstanceOf(Int64::class, $transaction->getFee());
        $this->assertTrue($transaction->getFee()->isEqualTo(100));
    }

    /**
     * @test
     * @covers ::getFee
     * @covers ::withFee
     */
    public function it_accepts_a_scaled_amount_fee()
    {
        $fee = ScaledAmount::of(100);
        $transaction = (new FeeBumpTransaction())->withFee($fee);

        $this->assertInstanceOf(FeeBumpTransaction::class, $transaction);
        $this->assertInstanceOf(Int64::class, $transaction->getFee());
        $this->assertEquals('1000000000', $transaction->getFee()->toNativeString());
    }

    /**
     * @test
     * @covers ::getInnerTransaction
     * @covers ::withInnerTransaction
     */
    public function it_accepts_an_inner_transaction()
    {
        $innerTransaction = new FeeBumpTransactionInnerTx();
        $transaction = (new FeeBumpTransaction())->withInnerTransaction($innerTransaction);

        $this->assertInstanceOf(FeeBumpTransaction::class, $transaction);
        $this->assertInstanceOf(FeeBumpTransactionInnerTx::class, $transaction->getInnerTransaction());
    }

    /**
     * @test
     * @covers ::getExtension
     * @covers ::withExtension
     */
    public function it_accepts_a_fee_bump_ext()
    {
        $ext = new FeeBumpTransactionExt();
        $transaction = (new FeeBumpTransaction())->withExtension($ext);

        $this->assertInstanceOf(FeeBumpTransaction::class, $transaction);
        $this->assertInstanceOf(FeeBumpTransactionExt::class, $transaction->getExtension());
    }
}
