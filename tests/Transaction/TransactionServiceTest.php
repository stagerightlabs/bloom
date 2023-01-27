<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Horizon\Error;
use StageRightLabs\Bloom\Horizon\OperationResourceCollection;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\Duration;
use StageRightLabs\Bloom\Transaction\FeeBumpTransaction;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\PreconditionsV2;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionService;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionService
 */
class TransactionServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function the_transaction_service_can_be_instantiated()
    {
        $bloom = new Bloom();
        $this->assertInstanceOf(TransactionService::class, $bloom->transaction);
    }

    /**
     * @test
     * @covers ::create
     */
    public function it_creates_transactions()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertTrue($sequenceNumber->isEqualTo($transaction->getSequenceNumber()));
    }

    /**
     * @test
     * @covers ::createFeeBumpTransaction
     */
    public function it_can_create_a_fee_bump_transaction()
    {
        $bloom = new Bloom();
        $envelope = TransactionEnvelope::wrapTransactionV1Envelope(new TransactionV1Envelope());
        $feeBumpTransaction = $bloom->transaction->createFeeBumpTransaction(
            envelope: $envelope,
            fee: 200,
            feeSource: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
        );

        $this->assertInstanceOf(FeeBumpTransaction::class, $feeBumpTransaction);
    }

    /**
     * @test
     * @covers ::addOperation
     */
    public function it_adds_operations_to_transactions()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = $bloom->transaction->create($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            startingBalance: '10',
            source: $account,
        );
        $modified = $bloom->transaction->addOperation($original, $operation);

        $this->assertInstanceOf(CreateAccountOp::class, $modified->getOperationList()->get(0)->getBody()->unwrap());
        $this->assertEquals(0, $original->getOperationCount());
        $this->assertEquals(1, $modified->getOperationCount());
    }

    /**
     * @test
     * @covers ::addMinimumTimePrecondition
     * @covers ::preconditions
     */
    public function it_can_add_a_minimum_time_precondition()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $minimumTime = TimePoint::fromUnixEpoch(1640995200);
        $transaction = $bloom->transaction->addMinimumTimePrecondition($transaction, $minimumTime);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals(1640995200, $transaction->getPreconditions()->getTimeBounds()->getMinTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::addMinimumTimePrecondition
     * @covers ::preconditions
     */
    public function it_can_add_a_minimum_time_precondition_overwriting_historical_timebounds()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31')));
        $minimumTime = TimePoint::fromUnixEpoch(1640995200);
        $transaction = $bloom->transaction->addMinimumTimePrecondition($transaction, $minimumTime);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals(1640995200, $transaction->getPreconditions()->getTimeBounds()->getMinTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::addMinimumTimePrecondition
     * @covers ::preconditions
     */
    public function it_can_add_a_minimum_time_precondition_overwriting_existing_v2_preconditions()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(Preconditions::wrapPreconditionsV2((new PreconditionsV2())->withMinimumTimePoint(0)));
        $minimumTime = TimePoint::fromUnixEpoch(1640995200);
        $transaction = $bloom->transaction->addMinimumTimePrecondition($transaction, $minimumTime);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals(1640995200, $transaction->getPreconditions()->getTimeBounds()->getMinTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::addMaximumTimePrecondition
     */
    public function it_can_add_a_maximum_time_precondition()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $maximumTime = TimePoint::fromUnixEpoch(1640995200);
        $transaction = $bloom->transaction->addMaximumTimePrecondition($transaction, $maximumTime);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals(1640995200, $transaction->getPreconditions()->getTimeBounds()->getMaxTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::setTimeout
     */
    public function it_can_set_a_transaction_to_expire_after_a_certain_number_of_seconds()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $transaction = $bloom->transaction->setTimeout($transaction, 60);

        $this->assertEquals(60, $transaction->getPreconditions()->getTimeBounds()->getInterval()->toNativeInt());
    }

    /**
     * @test
     * @covers ::addMinimumLedgerOffsetPrecondition
     */
    public function it_can_add_a_minimum_ledger_precondition()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $minLedger = UInt32::of(100);
        $transaction = $bloom->transaction->addMinimumLedgerOffsetPrecondition($transaction, $minLedger);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals(100, $transaction->getPreconditions()->getLedgerBounds()->getMinLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::addMaximumLedgerOffsetPrecondition
     */
    public function it_can_add_a_maximum_ledger_number_precondition()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $maxLedger = UInt32::of(1000);
        $transaction = $bloom->transaction->addMaximumLedgerOffsetPrecondition($transaction, $maxLedger);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals(1000, $transaction->getPreconditions()->getLedgerBounds()->getMaxLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::addMinimumSequenceNumberPrecondition
     */
    public function it_can_add_a_minimum_sequence_number_precondition()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $minimumSequenceNumber = SequenceNumber::of(10);
        $transaction = $bloom->transaction->addMinimumSequenceNumberPrecondition($transaction, $minimumSequenceNumber);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals('10', $transaction->getPreconditions()->getMinimumSequenceNumber()->toNativeString());
    }

    /**
     * @test
     * @covers ::addMinimumSequenceAgePrecondition
     */
    public function it_can_add_a_minimum_sequence_age_precondition()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $minimumSequenceNumberAge = Duration::of(10);
        $transaction = $bloom->transaction->addMinimumSequenceAgePrecondition($transaction, $minimumSequenceNumberAge);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals('10', $transaction->getPreconditions()->getMinimumSequenceAge()->toNativeString());
    }

    /**
     * @test
     * @covers ::addMinimumSequenceLedgerGapPrecondition
     */
    public function it_adds_a_minimum_sequence_ledger_gap_precondition()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $minimumSequenceLedgerGap = UInt32::of(10);
        $transaction = $bloom->transaction->addMinimumSequenceLedgerGapPrecondition($transaction, $minimumSequenceLedgerGap);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals(10, $transaction->getPreconditions()->getMinimumSequenceLedgerGap()->toNativeInt());
    }

    /**
     * @test
     * @covers ::removePreconditions
     * @covers ::preconditions
     */
    public function it_removes_preconditions()
    {
        $bloom = new Bloom();
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = $bloom->transaction->create($account, $sequenceNumber);
        $minimumSequenceNumber = SequenceNumber::of(10);
        $transaction = $bloom->transaction->addMinimumSequenceNumberPrecondition($transaction, $minimumSequenceNumber);
        $transaction = $bloom->transaction->removePreconditions($transaction);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals(XDR::VOID, $transaction->getPreconditions()->unwrap());
    }

    /**
     * @test
     * @covers ::preconditions
     */
    public function it_provides_a_preconditions_v2_set_if_nothing_else_is_defined()
    {
        $bloom = new Bloom();
        $transaction = (new Transaction())->withPreconditions(Preconditions::none());
        $minimumTime = TimePoint::fromUnixEpoch(1640995200);
        $transaction = $bloom->transaction->addMinimumTimePrecondition($transaction, $minimumTime);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(PreconditionsV2::class, $transaction->getPreconditions()->unwrap());
        $this->assertEquals(1640995200, $transaction->getPreconditions()->getTimeBounds()->getMinTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::retrieveOperations
     */
    public function it_can_retrieve_the_operations_for_a_transaction()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(Response::fake('transaction_operations'));
        $collection = $bloom->transaction->retrieveOperations('6b983a4e0dc3c04f4bd6b9037c55f70a09c434dfd01492be1077cf7ea68c2e4a');

        $this->assertInstanceOf(OperationResourceCollection::class, $collection);
    }

    /**
     * @test
     * @covers ::retrieveOperations
     */
    public function it_adjusts_for_invalid_operation_query_parameters()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(Response::fake('transaction_operations'));
        $collection = $bloom->transaction->retrieveOperations(
            transactionHash: '6b983a4e0dc3c04f4bd6b9037c55f70a09c434dfd01492be1077cf7ea68c2e4a',
            order: 'foo',
            limit: 1000
        );

        $this->assertInstanceOf(OperationResourceCollection::class, $collection);
        $this->assertEquals(
            'https://horizon.stellar.org/transactions/6b983a4e0dc3c04f4bd6b9037c55f70a09c434dfd01492be1077cf7ea68c2e4a/operations?cursor=&limit=10&order=asc',
            $collection->getSelfLink()
        );
    }

    /**
     * @test
     * @covers ::retrieveOperations
     */
    public function it_returns_an_error_if_the_operations_request_failed()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(Response::fake('generic_error', statusCode: 400));
        $collection = $bloom->transaction->retrieveOperations('6b983a4e0dc3c04f4bd6b9037c55f70a09c434dfd01492be1077cf7ea68c2e4a');

        $this->assertInstanceOf(Error::class, $collection);
    }
}
