<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\InnerTransactionResult;
use StageRightLabs\Bloom\Transaction\InnerTransactionResultPair;
use StageRightLabs\Bloom\Transaction\InnerTransactionResultResult;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\InnerTransactionResultPair
 */
class InnerTransactionResultPairTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $hash = Hash::make('1');
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapSuccessfulOperationResultList($operationResultList);
        $innerTransactionResult = (new InnerTransactionResult())->withResult($innerTransactionResultResult);
        $innerTransactionResultPair = (new InnerTransactionResultPair())
            ->withTransactionHash($hash)
            ->withInnerTransactionResult($innerTransactionResult);
        $buffer = XDR::fresh()->write($innerTransactionResultPair);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAAAAAAAAAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_transaction_hash_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapSuccessfulOperationResultList($operationResultList);
        $innerTransactionResult = (new InnerTransactionResult())->withResult($innerTransactionResultResult);
        $innerTransactionResultPair = (new InnerTransactionResultPair())
            ->withInnerTransactionResult($innerTransactionResult);
        XDR::fresh()->write($innerTransactionResultPair);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_inner_transaction_result_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $hash = Hash::make('1');
        $innerTransactionResultPair = (new InnerTransactionResultPair())
            ->withTransactionHash($hash);
        XDR::fresh()->write($innerTransactionResultPair);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $innerTransactionResultPair = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAAAAAAAAAAAAAAAAAA==')
            ->read(InnerTransactionResultPair::class);

        $this->assertInstanceOf(InnerTransactionResultPair::class, $innerTransactionResultPair);
        $this->assertInstanceOf(InnerTransactionResult::class, $innerTransactionResultPair->getInnerTransactionResult());
        $this->assertInstanceOf(Hash::class, $innerTransactionResultPair->getTransactionHash());
    }

    /**
     * @test
     * @covers ::withTransactionHash
     * @covers ::getTransactionHash
     */
    public function it_accepts_a_transaction_hash()
    {
        $innerTransactionResultPair = (new InnerTransactionResultPair())
            ->withTransactionHash(Hash::make('1'));

        $this->assertInstanceOf(Hash::class, $innerTransactionResultPair->getTransactionHash());
    }

    /**
     * @test
     * @covers ::withInnerTransactionResult
     * @covers ::getInnerTransactionResult
     */
    public function it_accepts_an_inner_transaction_result()
    {
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapSuccessfulOperationResultList($operationResultList);
        $innerTransactionResult = (new InnerTransactionResult())->withResult($innerTransactionResultResult);
        $innerTransactionResultPair = (new InnerTransactionResultPair())
            ->withInnerTransactionResult($innerTransactionResult);

        $this->assertInstanceOf(InnerTransactionResult::class, $innerTransactionResultPair->getInnerTransactionResult());
    }
}
