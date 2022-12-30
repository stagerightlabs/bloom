<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionResult;
use StageRightLabs\Bloom\Transaction\TransactionResultPair;
use StageRightLabs\Bloom\Transaction\TransactionResultResult;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionResultPair
 */
class TransactionResultPairTest extends TestCase
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
        $buffer = XDR::fresh()->write($transactionResultPair);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAQAAAAAAAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_transaction_hash_is_required_for_xdr_conversion()
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
            ->withResult($transactionResult);
        XDR::fresh()->write($transactionResultPair);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_transaction_result_is_required_for_()
    {
        $this->expectException(InvalidArgumentException::class);
        $transactionResultPair = (new TransactionResultPair())
            ->withTransactionHash(Hash::make('1'));
        XDR::fresh()->write($transactionResultPair);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionResultPair = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAQAAAAAAAAAAAAAAAA==')
            ->read(TransactionResultPair::class);

        $this->assertInstanceOf(TransactionResultPair::class, $transactionResultPair);
        $this->assertInstanceOf(Hash::class, $transactionResultPair->getTransactionHash());
        $this->assertInstanceOf(TransactionResult::class, $transactionResultPair->getResult());
    }

    /**
     * @test
     * @covers ::withTransactionHash
     * @covers ::getTransactionHash
     */
    public function it_accepts_a_transaction_hash()
    {
        $transactionResultPair = (new TransactionResultPair())
            ->withTransactionHash(Hash::make('1'));

        $this->assertInstanceOf(Hash::class, $transactionResultPair->getTransactionHash());
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
            ->withResult($transactionResult);

        $this->assertInstanceOf(TransactionResult::class, $transactionResultPair->getResult());
    }
}
