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
use StageRightLabs\Bloom\Envelope\TransactionV0Envelope;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayload;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayloadTaggedTransaction;
use StageRightLabs\Bloom\Transaction\TransactionV0;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Envelope\TransactionV0Envelope
 */
class TransactionV0EnvelopeTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = TransactionV0::for($account, $sequenceNumber);
        $transaction = $transaction->withTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'));
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $envelope = (new TransactionV0Envelope())
            ->withTransaction($transaction)
            ->withSignatures($signatureList);
        $buffer = XDR::fresh()->write($envelope);

        $this->assertEquals(
            'am1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAABkAAAAAAAAAAEAAAABAAAAAF/uZgAAAAAAYc5IAAAAAAAAAAAAAAAAAAAAAAFoaW50AAAACXNpZ25hdHVyZQAAAA==',
            $buffer->toBase64()
        );
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
        $envelope = (new TransactionV0Envelope())
            ->withSignatures($signatureList);
        XDR::fresh()->write($envelope);
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
        $transaction = TransactionV0::for($account, $sequenceNumber);
        $transaction = $transaction->withTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'));
        $envelope = (new TransactionV0Envelope())->withTransaction($transaction);
        XDR::fresh()->write($envelope);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $envelope = XDR::fromBase64('am1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAABkAAAAAAAAAAEAAAABAAAAAF/uZgAAAAAAYc5IAAAAAAAAAAAAAAAAAAAAAAFoaW50AAAACXNpZ25hdHVyZQAAAA==')
            ->read(TransactionV0Envelope::class);

        $this->assertInstanceOf(TransactionV0Envelope::class, $envelope);
        $this->assertInstanceOf(TransactionV0::class, $envelope->getTransaction());
        $this->assertInstanceOf(DecoratedSignatureList::class, $envelope->getSignatures());
    }

    /**
     * @test
     * @covers ::getTransaction
     * @covers ::withTransaction
     */
    public function it_accepts_a_transaction()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = TransactionV0::for($account, $sequenceNumber);
        $transaction = $transaction->withTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'));
        $envelope = (new TransactionV0Envelope())->withTransaction($transaction);

        $this->assertInstanceOf(TransactionV0Envelope::class, $envelope);
        $this->assertInstanceOf(TransactionV0::class, $envelope->getTransaction());
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
        $envelope = (new TransactionV0Envelope())->withSignatures($signatureList);

        $this->assertInstanceOf(TransactionV0Envelope::class, $envelope);
        $this->assertInstanceOf(DecoratedSignatureList::class, $envelope->getSignatures());
    }

    /**
     * @test
     * @covers ::addSignature
     */
    public function it_accepts_additional_signatures()
    {
        $signatureList = DecoratedSignatureList::empty();
        $transactionV0Envelope = (new TransactionV0Envelope())->withSignatures($signatureList);
        $this->assertCount(0, $transactionV0Envelope->getSignatures());

        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $transactionV0Envelope = $transactionV0Envelope->addSignature($signature);
        $this->assertCount(1, $transactionV0Envelope->getSignatures());
    }

    /**
     * @test
     * @covers ::withEmptySignatureList
     */
    public function it_initializes_an_empty_signature_list()
    {
        $envelope = (new TransactionV0Envelope())->withEmptySignatureList();

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
        $transaction = TransactionV0::for($account, $sequenceNumber);
        $transaction = $transaction->withTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'));
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $envelope = (new TransactionV0Envelope())
            ->withTransaction($transaction)
            ->withSignatures($signatureList);

        $signaturePayload = $envelope->getSignaturePayload(Bloom::TEST_NETWORK_PASSPHRASE);

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
        $transaction = TransactionV0::for($account, $sequenceNumber);
        $transaction = $transaction->withTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'));
        $signature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $signatureList = DecoratedSignatureList::of([$signature]);
        $envelope = (new TransactionV0Envelope())
            ->withTransaction($transaction)
            ->withSignatures($signatureList);

        $hash = $envelope->getHash(Bloom::TEST_NETWORK_PASSPHRASE);

        $this->assertInstanceOf(Hash::class, $hash);
        $this->assertEquals('e8bd5964ca5062058e27f4194f07adeb4b5f6dc54ac5bce2c57596673971b962', $hash->toHex());
    }
}
