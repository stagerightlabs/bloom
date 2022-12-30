<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Envelope;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\DecoratedSignatureList;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Horizon\TransactionResource;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\Transaction;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Envelope\EnvelopeService
 */
class EnvelopeServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::enclose
     */
    public function it_encloses_a_transaction_with_a_transaction_envelope()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);

        $envelope = $bloom->envelope->enclose($transaction);

        $this->assertInstanceOf(TransactionEnvelope::class, $envelope);
    }

    /**
     * @test
     * @covers ::sign
     */
    public function it_can_sign_a_transaction_envelope()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $transaction = Transaction::for($account, $sequenceNumber);
        $envelope = $bloom->envelope->enclose($transaction);

        $this->assertEquals(0, $envelope->getSignatures()->count());

        $envelope = $bloom->envelope->sign($envelope, $account);

        $this->assertEquals(1, $envelope->getSignatures()->count());
    }

    /**
     * @test
     * @covers ::sign
     */
    public function it_cannot_sign_a_transaction_envelope_without_a_valid_envelope_hash()
    {
        $this->expectException(InvalidArgumentException::class);
        $bloom = new Bloom();
        $account = Account::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');

        $bloom->envelope->sign(new TransactionEnvelope(), $account);
    }

    /**
     * @test
     * @covers ::sign
     */
    public function it_cannot_sign_a_transaction_envelope_without_a_valid_signer()
    {
        $this->expectException(InvalidKeyException::class);
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $transaction = Transaction::for($account, $sequenceNumber);
        $envelope = $bloom->envelope->enclose($transaction);

        $bloom->envelope->sign($envelope, new Account());
    }

    /**
     * @test
     * @covers ::post
     */
    public function it_can_post_a_transaction_to_horizon()
    {
        $bloom = Bloom::fake();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $transaction = Transaction::for($account, $sequenceNumber);
        $envelope = $bloom->envelope->enclose($transaction);
        $envelope = $bloom->envelope->sign($envelope, $account);
        $bloom->horizon->withResponse(Response::fake('transaction_submitted', [], 200));

        $response = $bloom->envelope->post($envelope);

        $this->assertInstanceOf(TransactionResource::class, $response);
        $this->assertEquals(200, $response->getResponse()->getStatusCode());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_return_a_base64_xdr_string()
    {
        $bloom = new Bloom();
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
        $envelope = (new TransactionV1Envelope())
            ->withTransaction($transaction)
            ->withSignatures($signatureList);
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope($envelope);

        $this->assertEquals(
            'AAAAAgAAAABqbUHHOUNTgIZpeXjQQHgNYcXOazSxcCrhBCh2M4Od7gAAAGQAAAAAAAAAAQAAAAEAAAAAX+5mAAAAAABhzkgAAAAAAAAAAAAAAAAAAAAAAWhpbnQAAAAJc2lnbmF0dXJlAAAA',
            $bloom->envelope->toXdr($envelope)
        );
    }

    /**
     * @test
     * @covers ::fromXdr
     */
    public function it_can_interpret_base64_xdr_strings()
    {
        $bloom = new Bloom();
        $envelope = $bloom->envelope->fromXdr('AAAAAgAAAABqbUHHOUNTgIZpeXjQQHgNYcXOazSxcCrhBCh2M4Od7gAAAGQAAAAAAAAAAQAAAAEAAAAAX+5mAAAAAABhzkgAAAAAAAAAAAAAAAAAAAAAAWhpbnQAAAAJc2lnbmF0dXJlAAAA');

        $this->assertInstanceOf(TransactionEnvelope::class, $envelope);
    }

    /**
     * @test
     * @covers ::fromXdr
     */
    public function it_cannot_interpret_invalid_xdr()
    {
        $this->expectException(InvalidArgumentException::class);

        $bloom = new Bloom();
        $bloom->envelope->fromXdr('INVALIDXDR');
    }
}
