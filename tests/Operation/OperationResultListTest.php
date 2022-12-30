<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\OperationResult;
use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Operation\OperationResultTr;
use StageRightLabs\Bloom\Operation\PaymentResult;
use StageRightLabs\Bloom\Operation\PaymentResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationResultList
 */
class OperationResultListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(OperationResult::class, OperationResultList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(OperationResultList::MAX_LENGTH, OperationResultList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $this->assertEmpty(OperationResultList::empty());
    }

    /**
     * @test
     * @covers ::getSummary
     */
    public function it_returns_an_array_of_error_messages()
    {
        $paymentResult = PaymentResult::simulate(PaymentResultCode::malformed());
        $operationResultTr = OperationResultTr::wrapPaymentResult($paymentResult);
        $operationResult = OperationResult::wrapOperationResultTr($operationResultTr);
        $operationResultList = OperationResultList::of($operationResult);

        $this->assertCount(1, $operationResultList->getSummary());
        $this->assertEquals('payment_result', $operationResultList->getSummary()[0]['operation']);
        $this->assertEquals('payment_malformed', $operationResultList->getSummary()[0]['code']);
        $this->assertNotNull($operationResultList->getSummary()[0]['message']);
    }
}
