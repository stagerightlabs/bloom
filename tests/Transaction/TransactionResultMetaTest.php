<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Operation\OperationMetaList;
use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionMeta;
use StageRightLabs\Bloom\Transaction\TransactionResult;
use StageRightLabs\Bloom\Transaction\TransactionResultMeta;
use StageRightLabs\Bloom\Transaction\TransactionResultPair;
use StageRightLabs\Bloom\Transaction\TransactionResultResult;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionResultMeta
 */
class TransactionResultMetaTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);
        $feeCharged = Int64::of(1);
        $transactionResult = (new TransactionResult())
            ->withFeeCharged($feeCharged)
            ->withResult($transactionResultResult);
        $transactionResultPair = (new TransactionResultPair())
            ->withTransactionHash(Hash::make('1'))
            ->withResult($transactionResult);
        $transactionMeta = TransactionMeta::wrapOperationMetaList(OperationMetaList::empty());
        $transactionResultMeta = (new TransactionResultMeta())
            ->withResult($transactionResultPair)
            ->withFeeProcessing(LedgerEntryChanges::empty())
            ->withTxApplyProcessing($transactionMeta);
        $buffer = XDR::fresh()->write($transactionResultMeta);

        $this->assertEquals(
            'a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_result_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $transactionMeta = TransactionMeta::wrapOperationMetaList(OperationMetaList::empty());
        $transactionResultMeta = (new TransactionResultMeta())
            ->withFeeProcessing(LedgerEntryChanges::empty())
            ->withTxApplyProcessing($transactionMeta);
        XDR::fresh()->write($transactionResultMeta);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function fee_processing_information_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);
        $feeCharged = Int64::of(1);
        $transactionResult = (new TransactionResult())
            ->withFeeCharged($feeCharged)
            ->withResult($transactionResultResult);
        $transactionResultPair = (new TransactionResultPair())
            ->withTransactionHash(Hash::make('1'))
            ->withResult($transactionResult);
        $transactionMeta = TransactionMeta::wrapOperationMetaList(OperationMetaList::empty());
        $transactionResultMeta = (new TransactionResultMeta())
            ->withResult($transactionResultPair)
            ->withTxApplyProcessing($transactionMeta);
        XDR::fresh()->write($transactionResultMeta);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function transaction_processing_information_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);
        $feeCharged = Int64::of(1);
        $transactionResult = (new TransactionResult())
            ->withFeeCharged($feeCharged)
            ->withResult($transactionResultResult);
        $transactionResultPair = (new TransactionResultPair())
            ->withTransactionHash(Hash::make('1'))
            ->withResult($transactionResult);
        $transactionResultMeta = (new TransactionResultMeta())
            ->withResult($transactionResultPair)
            ->withFeeProcessing(LedgerEntryChanges::empty());
        XDR::fresh()->write($transactionResultMeta);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionResultMeta = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==')
            ->read(TransactionResultMeta::class);

        $this->assertInstanceOf(TransactionResultMeta::class, $transactionResultMeta);
        $this->assertInstanceOf(TransactionResultPair::class, $transactionResultMeta->getResult());
        $this->assertInstanceOf(LedgerEntryChanges::class, $transactionResultMeta->getFeeProcessing());
        $this->assertInstanceOf(TransactionMeta::class, $transactionResultMeta->getTxApplyProcessing());
    }

    /**
     * @test
     * @covers ::withResult
     * @covers ::getResult
     */
    public function it_accepts_a_result()
    {
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);
        $feeCharged = Int64::of(1);
        $transactionResult = (new TransactionResult())
            ->withFeeCharged($feeCharged)
            ->withResult($transactionResultResult);
        $transactionResultPair = (new TransactionResultPair())
            ->withTransactionHash(Hash::make('1'))
            ->withResult($transactionResult);
        $transactionResultMeta = (new TransactionResultMeta())
            ->withResult($transactionResultPair);

        $this->assertInstanceOf(TransactionResultMeta::class, $transactionResultMeta);
        $this->assertInstanceOf(TransactionResultPair::class, $transactionResultMeta->getResult());
    }

    /**
     * @test
     * @covers ::withFeeProcessing
     * @covers ::getFeeProcessing
     */
    public function it_accepts_fee_processing_information()
    {
        $transactionResultMeta = (new TransactionResultMeta())
            ->withFeeProcessing(LedgerEntryChanges::empty());

        $this->assertInstanceOf(TransactionResultMeta::class, $transactionResultMeta);
        $this->assertInstanceOf(LedgerEntryChanges::class, $transactionResultMeta->getFeeProcessing());
    }

    /**
     * @test
     * @covers ::withTxApplyProcessing
     * @covers ::getTxApplyProcessing
     */
    public function it_accepts_transaction_processing_information()
    {
        $transactionMeta = TransactionMeta::wrapOperationMetaList(OperationMetaList::empty());
        $transactionResultMeta = (new TransactionResultMeta())
            ->withTxApplyProcessing($transactionMeta);

        $this->assertInstanceOf(TransactionResultMeta::class, $transactionResultMeta);
        $this->assertInstanceOf(TransactionMeta::class, $transactionResultMeta->getTxApplyProcessing());
    }
}
