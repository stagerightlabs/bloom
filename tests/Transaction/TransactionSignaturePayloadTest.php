<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayload;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayloadTaggedTransaction;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionSignaturePayload
 */
class TransactionSignaturePayloadTest extends TestCase
{
    /**
     * @test
     * @covers ::for
     */
    public function it_can_be_instantiated_statically_wth_a_hash_network_id()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(
            Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
        );
        $taggedTransaction = TransactionSignaturePayloadTaggedTransaction::wrapTransaction($transaction);
        $networkId = Hash::make('network_id');

        $transactionSignaturePayload = TransactionSignaturePayload::for($taggedTransaction, $networkId);

        $this->assertInstanceOf(TransactionSignaturePayload::class, $transactionSignaturePayload);
        $this->assertInstanceOf(Hash::class, $transactionSignaturePayload->getNetworkId());
        $this->assertInstanceOf(
            TransactionSignaturePayloadTaggedTransaction::class,
            $transactionSignaturePayload->getTaggedTransaction()
        );
    }

    /**
     * @test
     * @covers ::for
     */
    public function it_can_be_instantiated_statically_wth_a_string_network_id()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(
            Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
        );
        $taggedTransaction = TransactionSignaturePayloadTaggedTransaction::wrapTransaction($transaction);

        $transactionSignaturePayload = TransactionSignaturePayload::for($taggedTransaction, 'network_id');

        $this->assertInstanceOf(TransactionSignaturePayload::class, $transactionSignaturePayload);
        $this->assertInstanceOf(Hash::class, $transactionSignaturePayload->getNetworkId());
        $this->assertInstanceOf(
            TransactionSignaturePayloadTaggedTransaction::class,
            $transactionSignaturePayload->getTaggedTransaction()
        );
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
        $transaction = $transaction->withPreconditions(
            Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
        );
        $taggedTransaction = TransactionSignaturePayloadTaggedTransaction::wrapTransaction($transaction);
        $networkId = Hash::make(Bloom::TEST_NETWORK_PASSPHRASE);

        $transactionSignaturePayload = (new TransactionSignaturePayload())
            ->withNetworkId($networkId)
            ->withTaggedTransaction($taggedTransaction);

        $buffer = XDR::fresh()->write($transactionSignaturePayload);

        $this->assertEquals(
            'zuAwLVmETTK9ypFcggPdRLM/u37cGQUeo3q+3yjs1HIAAAACAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAZAAAAAAAAAABAAAAAQAAAABf7mYAAAAAAGHOSAAAAAAAAAAAAAAAAAA=',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_network_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(
            Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
        );
        $taggedTransaction = TransactionSignaturePayloadTaggedTransaction::wrapTransaction($transaction);
        $transactionSignaturePayload = (new TransactionSignaturePayload())
            ->withTaggedTransaction($taggedTransaction);
        XDR::fresh()->write($transactionSignaturePayload);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_tagged_transaction_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $transactionSignaturePayload = (new TransactionSignaturePayload())
            ->withNetworkId(Hash::make(Bloom::TEST_NETWORK_PASSPHRASE));
        XDR::fresh()->write($transactionSignaturePayload);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionSignaturePayload = XDR::fromBase64('zuAwLVmETTK9ypFcggPdRLM/u37cGQUeo3q+3yjs1HIAAAACAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAZAAAAAAAAAABAAAAAQAAAABf7mYAAAAAAGHOSAAAAAAAAAAAAAAAAAA=')
            ->read(TransactionSignaturePayload::class);

        $this->assertInstanceOf(TransactionSignaturePayload::class, $transactionSignaturePayload);
        $this->assertInstanceOf(Hash::class, $transactionSignaturePayload->getNetworkId());
        $this->assertInstanceOf(
            TransactionSignaturePayloadTaggedTransaction::class,
            $transactionSignaturePayload->getTaggedTransaction()
        );
    }

    /**
     * @test
     * @covers ::getNetworkId
     * @covers ::withNetworkId
     */
    public function it_accepts_a_hash_network_id()
    {
        $transactionSignaturePayload = (new TransactionSignaturePayload())
            ->withNetworkId(Hash::make('network_id'));

        $this->assertInstanceOf(TransactionSignaturePayload::class, $transactionSignaturePayload);
        $this->assertInstanceOf(Hash::class, $transactionSignaturePayload->getNetworkId());
    }

    /**
     * @test
     * @covers ::getNetworkId
     * @covers ::withNetworkId
     */
    public function it_accepts_a_string_network_id()
    {
        $transactionSignaturePayload = (new TransactionSignaturePayload())
            ->withNetworkId('network_id');

        $this->assertInstanceOf(TransactionSignaturePayload::class, $transactionSignaturePayload);
        $this->assertInstanceOf(Hash::class, $transactionSignaturePayload->getNetworkId());
    }

    /**
     * @test
     * @covers ::getTaggedTransaction
     * @covers ::withTaggedTransaction
     */
    public function it_accepts_a_tagged_transaction()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(
            Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
        );
        $taggedTransaction = TransactionSignaturePayloadTaggedTransaction::wrapTransaction($transaction);

        $transactionSignaturePayload = (new TransactionSignaturePayload())
            ->withTaggedTransaction($taggedTransaction);

        $this->assertInstanceOf(TransactionSignaturePayload::class, $transactionSignaturePayload);
        $this->assertInstanceOf(
            TransactionSignaturePayloadTaggedTransaction::class,
            $transactionSignaturePayload->getTaggedTransaction()
        );
    }
}
