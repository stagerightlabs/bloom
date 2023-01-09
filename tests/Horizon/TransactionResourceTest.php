<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\DecoratedSignatureList;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Horizon\TransactionResource;
use StageRightLabs\Bloom\Operation\OperationMeta;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionMeta;
use StageRightLabs\Bloom\Transaction\TransactionResult;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\TransactionResource
 */
class TransactionResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getSelfLink
     */
    public function it_returns_the_self_link()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = 'https://horizon-testnet.stellar.org/transactions/[hash]';

        $this->assertEquals($expected, $transaction->getSelfLink());
    }

    /**
     * @test
     * @covers ::getAccountLink
     */
    public function it_returns_the_account_link()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction', ['address' => 'ABCD'])->getBody()
        );
        $expected = 'https://horizon-testnet.stellar.org/accounts/ABCD';

        $this->assertEquals($expected, $transaction->getAccountLink());
    }

    /**
     * @test
     * @covers ::getLedgerLink
     */
    public function it_returns_the_ledger_link()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = 'https://horizon-testnet.stellar.org/ledgers/994571';

        $this->assertEquals($expected, $transaction->getLedgerLink());
    }

    /**
     * @test
     * @covers ::getOperationsLink
     */
    public function it_returns_the_operations_link()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = 'https://horizon-testnet.stellar.org/transactions/[hash]/operations{?cursor,limit,order}';

        $this->assertEquals($expected, $transaction->getOperationsLink());
    }

    /**
     * @test
     * @covers ::getEffectsLink
     */
    public function it_returns_the_effects_link()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = 'https://horizon-testnet.stellar.org/transactions/[hash]/effects{?cursor,limit,order}';

        $this->assertEquals($expected, $transaction->getEffectsLink());
    }

    /**
     * @test
     * @covers ::getPrecedesLink
     */
    public function it_returns_the_precedes_link()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = 'https://horizon-testnet.stellar.org/transactions?order=asc&cursor=4271649918558208';

        $this->assertEquals($expected, $transaction->getPrecedesLink());
    }

    /**
     * @test
     * @covers ::getSucceedsLink
     */
    public function it_returns_the_succeeds_link()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = 'https://horizon-testnet.stellar.org/transactions?order=desc&cursor=4271649918558208';

        $this->assertEquals($expected, $transaction->getSucceedsLink());
    }

    /**
     * @test
     * @covers ::getId
     */
    public function it_returns_the_transaction_id()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = '[hash]';

        $this->assertEquals($expected, $transaction->getId());
    }

    /**
     * @test
     * @covers ::getPagingToken
     */
    public function it_returns_the_paging_token()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = '4271649918558208';

        $this->assertEquals($expected, $transaction->getPagingToken());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     */
    public function it_returns_the_success_status()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $this->assertTrue($transaction->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getHash
     */
    public function it_returns_the_transaction_hash()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = '[hash]';

        $this->assertEquals($expected, $transaction->getHash());
    }

    /**
     * @test
     * @covers ::getLedgerSequence
     */
    public function it_returns_the_ledger_sequence()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = '994571';

        $this->assertEquals($expected, $transaction->getLedgerSequence());
    }

    /**
     * @test
     * @covers ::getCreatedAt
     */
    public function it_returns_the_created_at_date()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = new \DateTime('2022-05-15T21:41:56Z');

        $this->assertEquals($expected, $transaction->getCreatedAt());
    }

    /**
     * @test
     * @covers ::getCreatedAt
     */
    public function it_returns_null_for_created_at_if_no_date_is_present()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_without_created_at')->getBody()
        );
        $this->assertNull($transaction->getCreatedAt());
    }

    /**
     * @test
     * @covers ::getSourceAccountAddress
     */
    public function it_returns_the_source_account_address()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction', ['address' => 'ABCD'])->getBody()
        );
        $expected = 'ABCD';

        $this->assertEquals($expected, $transaction->getSourceAccountAddress());
    }

    /**
     * @test
     * @covers ::getSourceAccountSequence
     */
    public function it_returns_the_source_account_sequence()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = '3133127102824503';

        $this->assertEquals($expected, $transaction->getSourceAccountSequence());
    }

    /**
     * @test
     * @covers ::getFeeCharged
     */
    public function it_returns_the_fee_charged()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $this->assertEquals('0.0010000', $transaction->getFeeCharged()->toNativeString());
    }

    /**
     * @test
     * @covers ::getFeeCharged
     */
    public function it_returns_null_for_missing_fee_charged()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_without_fee_charged')->getBody()
        );
        $this->assertNull($transaction->getFeeCharged());
    }

    /**
     * @test
     * @covers ::getMaxFee
     */
    public function it_returns_the_max_fee()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $this->assertEquals('0.1000000', $transaction->getMaxFee()->toNativeString());
    }

    /**
     * @test
     * @covers ::getMaxFee
     */
    public function it_returns_null_for_missing_max_fee()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_without_max_fee')->getBody()
        );
        $this->assertNull($transaction->getMaxFee());
    }

    /**
     * @test
     * @covers ::getOperationCount
     */
    public function it_returns_the_operation_count()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $this->assertEquals(1, $transaction->getOperationCount());
    }

    /**
     * @test
     * @covers ::getEnvelopeXdr
     */
    public function it_returns_the_envelope_xdr()
    {
        $transactionResource = TransactionResource::wrap(
            Response::fake('transaction_submitted')->getBody()
        );
        $expected = '[envelope_xdr]';

        $this->assertEquals($expected, $transactionResource->getEnvelopeXdr());
    }

    /**
     * @test
     * @covers ::getEnvelope
     */
    public function it_returns_the_transaction_envelope()
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
        $envelope = (new TransactionV1Envelope())
            ->withTransaction($transaction)
            ->withSignatures($signatureList);
        $buffer = XDR::fresh()->write($envelope);

        $transactionResource = TransactionResource::wrap(
            Response::fake(
                'transaction_submitted',
                [
                    'envelope_xdr' => $buffer->toBase64()
                ]
            )->getBody()
        );

        $decoded = $transactionResource->getEnvelope();

        $this->assertInstanceOf(TransactionEnvelope::class, $decoded);
    }

    /**
     * @test
     * @covers ::getEnvelope
     */
    public function the_envelope_returns_null_if_xdr_decoding_fails()
    {
        $transactionResource = TransactionResource::wrap(
            Response::fake('transaction_submitted')->getBody()
        );
        $this->assertNull($transactionResource->getEnvelope());
    }

    /**
     * @test
     * @covers ::getResultXdr
     */
    public function it_returns_the_result_xdr()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = 'AAAAAAAAAGQAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAA=';

        $this->assertEquals($expected, $transaction->getResultXdr());
    }

    /**
     * @test
     * @covers ::getResult
     */
    public function it_returns_the_decoded_result()
    {
        $transactionA = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $transactionB = TransactionResource::wrap([]);

        $this->assertInstanceOf(TransactionResult::class, $transactionA->getResult());
        $this->assertNull($transactionB->getResult());
    }

    /**
     * @test
     * @covers ::getResultMetaXdr
     */
    public function it_returns_the_result_meta_xdr()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = 'AAAAAgAAAAIAAAADABZvBAAAAAAAAAAAvVwFT3+rSlZXIxuRDhJnGNK1hmUGgR7Iz1Fs6Mi8yPgAAAAAPDNgHAAWaesAAAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAABABZvBAAAAAAAAAAAvVwFT3+rSlZXIxuRDhJnGNK1hmUGgR7Iz1Fs6Mi8yPgAAAAAPDNgHAAWaesAAAABAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAACAAAAAAAAAAAAAAAAAAAAAwAAAAAAFm8EAAAAAGOXevYAAAAAAAAAAQAAAAMAAAADABZvAgAAAAAAAAAAEH3Rayw4M0iCLoEe96rPFNGYim8AVHJU0z4ebYZW4JwAXyFoHjXfuAAAATsAAAHXAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAACAAAAAAAAAAAAAAAAAAAAAwAAAAAAFmoCAAAAAGOXYMUAAAAAAAAAAQAWbwQAAAAAAAAAABB90WssODNIgi6BHveqzxTRmIpvAFRyVNM+Hm2GVuCcAF8hUNW+97gAAAE7AAAB1wAAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAgAAAAAAAAAAAAAAAAAAAAMAAAAAABZqAgAAAABjl2DFAAAAAAAAAAAAFm8EAAAAAAAAAACgG04mjMWmD5gmfeWpY2/Ogov+50UUsejfFOdqR9nGMAAAABdIdugAABZvBAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAA=';

        $this->assertEquals($expected, $transaction->getResultMetaXdr());
    }

    /**
     * @test
     * @covers ::getResultMeta
     */
    public function it_returns_the_decoded_result_meta()
    {
        $transactionA = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $transactionB = TransactionResource::wrap([]);

        $this->assertInstanceOf(TransactionMeta::class, $transactionA->getResultMeta());
        $this->assertNull($transactionB->getResultMeta());
    }

    /**
     * @test
     * @covers ::getFeeMetaXdr
     */
    public function it_returns_the_fee_meta_xdr()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = 'AAAAAgAAAAMAFmnrAAAAAAAAAAC9XAVPf6tKVlcjG5EOEmcY0rWGZQaBHsjPUWzoyLzI+AAAAAA8M2CAABZp6wAAAAAAAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAEAFm8EAAAAAAAAAAC9XAVPf6tKVlcjG5EOEmcY0rWGZQaBHsjPUWzoyLzI+AAAAAA8M2AcABZp6wAAAAAAAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAA==';

        $this->assertEquals($expected, $transaction->getFeeMetaXdr());
    }

    /**
     * @test
     * @covers ::getFeeMeta
     */
    public function it_returns_the_decoded_fee_meta()
    {
        $transactionA = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $transactionB = TransactionResource::wrap([]);

        $this->assertInstanceOf(OperationMeta::class, $transactionA->getFeeMeta());
        $this->assertNull($transactionB->getFeeMeta());
    }

    /**
     * @test
     * @covers ::getMemo
     */
    public function it_returns_the_memo()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_text_memo')->getBody()
        );
        $expected = '298424';

        $this->assertEquals($expected, $transaction->getMemo());
    }

    /**
     * @test
     * @covers ::getMemoType
     */
    public function it_returns_the_memo_type()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_text_memo')->getBody()
        );
        $expected = 'text';

        $this->assertEquals($expected, $transaction->getMemoType());
    }

    /**
     * @test
     * @covers ::getSignatures
     */
    public function it_returns_the_signatures()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('friendbot_transaction')->getBody()
        );
        $expected = [
            "[signature1]",
            "[signature2]"
        ];

        $this->assertEquals($expected, $transaction->getSignatures());
    }

    /**
     * @test
     * @covers ::getPreconditions
     */
    public function it_returns_the_preconditions()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions')->getBody()
        );
        $expected = [
            'time_bounds'                     => [
                'min_time' => '1643673600',
                'max_time' => '1643673600',
            ],
            'ledger_bounds'                   => [
                'min_ledger' => 100,
                'max_ledger' => 200,
            ],
            'min_account_sequence'            => '100',
            'min_account_sequence_age'        => '200',
            'min_account_sequence_ledger_gap' => 1,
            'extra_signers'                   => [
                'SIGNERA',
                'SIGNERB',
            ]
        ];

        $this->assertEquals($expected, $transaction->getPreconditions());
    }

    /**
     * @test
     * @covers ::getMinTimeCondition
     */
    public function it_returns_the_min_time_condition()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions')->getBody()
        );
        $expected = new \Datetime('@1643673600');

        $this->assertEquals($expected, $transaction->getMinTimeCondition());
    }

    /**
     * @test
     * @covers ::getMinTimeCondition
     */
    public function it_returns_null_for_a_min_time_condition_of_zero()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions_alt')->getBody()
        );

        $this->assertNull($transaction->getMinTimeCondition());
    }

    /**
     * @test
     * @covers ::getMaxTimeCondition
     */
    public function it_returns_the_max_time_condition()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions')->getBody()
        );
        $expected = new \Datetime('@1643673600');

        $this->assertEquals($expected, $transaction->getMaxTimeCondition());
    }

    /**
     * @test
     * @covers ::getMaxTimeCondition
     */
    public function it_returns_null_for_a_max_time_condition_of_zero()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions_alt')->getBody()
        );
        $this->assertNull($transaction->getMaxTimeCondition());
    }

    /**
     * @test
     * @covers ::getMinLedgerCondition
     */
    public function it_returns_the_min_ledger_condition()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions')->getBody()
        );
        $expected = 100;

        $this->assertEquals($expected, $transaction->getMinLedgerCondition());
    }

    /**
     * @test
     * @covers ::getMaxLedgerCondition
     */
    public function it_returns_the_max_ledger_condition()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions')->getBody()
        );
        $expected = 200;

        $this->assertEquals($expected, $transaction->getMaxLedgerCondition());
    }

    /**
     * @test
     * @covers ::getMinAccountSequenceCondition
     */
    public function it_returns_the_min_account_sequence_condition()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions')->getBody()
        );
        $expected = Int64::of('100');

        $this->assertEquals($expected, $transaction->getMinAccountSequenceCondition());
    }

    /**
     * @test
     * @covers ::getMinAccountSequenceAgeCondition
     */
    public function it_returns_the_min_account_sequence_age_condition()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions')->getBody()
        );
        $expected = UInt64::of('200');

        $this->assertTrue($expected->isEqualTo($transaction->getMinAccountSequenceAgeCondition()));
    }

    /**
     * @test
     * @covers ::getMinAccountSequenceAgeCondition
     */
    public function it_returns_null_for_a_missing_min_account_sequence_age_condition()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions_alt')->getBody()
        );

        $this->assertNull($transaction->getMinAccountSequenceAgeCondition());
    }

    /**
     * @test
     * @covers ::getMinAccountSequenceLedgerGapCondition
     */
    public function it_returns_the_min_account_sequence_ledger_gap_condition()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions')->getBody()
        );
        $expected = 1;

        $this->assertEquals($expected, $transaction->getMinAccountSequenceLedgerGapCondition());
    }

    /**
     * @test
     * @covers ::getExtraSignersCondition
     */
    public function it_returns_the_extra_signers_condition()
    {
        $transaction = TransactionResource::wrap(
            Response::fake('transaction_with_preconditions')->getBody()
        );
        $expected = [
            'SIGNERA',
            'SIGNERB',
        ];

        $this->assertEquals($expected, $transaction->getExtraSignersCondition());
    }
}
