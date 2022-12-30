<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\InnerTransactionResult;
use StageRightLabs\Bloom\Transaction\InnerTransactionResultResult;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\InnerTransactionResult
 */
class InnerTransactionResultTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapSuccessfulOperationResultList($operationResultList);
        $innerTransactionResult = (new InnerTransactionResult())->withResult($innerTransactionResultResult);
        $buffer = XDR::fresh()->write($innerTransactionResult);

        $this->assertEquals('AAAAAAAAAAAAAAAAAAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_inner_transaction_result_result_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new InnerTransactionResult());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $innerTransactionResult = XDR::fromBase64('AAAAAAAAAAAAAAAAAAAAAAAAAAA=')
            ->read(InnerTransactionResult::class);

        $this->assertInstanceOf(InnerTransactionResult::class, $innerTransactionResult);
        $this->assertInstanceOf(InnerTransactionResultResult::class, $innerTransactionResult->getResult());
    }

    /**
     * @test
     * @covers ::withResult
     * @covers ::getResult
     */
    public function it_accepts_an_inner_transaction_result_result()
    {
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapSuccessfulOperationResultList($operationResultList);
        $innerTransactionResult = (new InnerTransactionResult())->withResult($innerTransactionResultResult);

        $this->assertInstanceOf(InnerTransactionResultResult::class, $innerTransactionResult->getResult());
    }
}
