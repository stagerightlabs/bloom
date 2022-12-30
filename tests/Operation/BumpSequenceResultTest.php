<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\BumpSequenceResult;
use StageRightLabs\Bloom\Operation\BumpSequenceResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\BumpSequenceResult
 */
class BumpSequenceResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(BumpSequenceResultCode::class, BumpSequenceResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            BumpSequenceResultCode::BUMP_SEQUENCE_SUCCESS => XDR::VOID,
            BumpSequenceResultCode::BUMP_SEQUENCE_BAD_SEQ => XDR::VOID,
        ];

        $this->assertEquals($expected, BumpSequenceResult::arms());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $bumpSequenceResult = BumpSequenceResult::simulate(BumpSequenceResultCode::badSequenceNumber());

        $this->assertInstanceOf(BumpSequenceResult::class, $bumpSequenceResult);
        $this->assertEquals(BumpSequenceResultCode::BUMP_SEQUENCE_BAD_SEQ, $bumpSequenceResult->getType());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $bumpSequenceResult = BumpSequenceResult::success();
        $this->assertEquals(BumpSequenceResultCode::BUMP_SEQUENCE_SUCCESS, $bumpSequenceResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_type_is_set()
    {
        $this->assertNull((new BumpSequenceResult())->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $bumpSequenceResultA = BumpSequenceResult::success();
        $bumpSequenceResultB = new BumpSequenceResult();

        $this->assertTrue($bumpSequenceResultA->wasSuccessful());
        $this->assertFalse($bumpSequenceResultA->wasNotSuccessful());
        $this->assertTrue($bumpSequenceResultB->wasNotSuccessful());
        $this->assertFalse($bumpSequenceResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $bumpSequenceResult = BumpSequenceResult::simulate(BumpSequenceResultCode::badSequenceNumber());

        $this->assertNotEmpty($bumpSequenceResult->getErrorMessage());
        $this->assertEquals('bump_sequence_bad_seq', $bumpSequenceResult->getErrorCode());
        $this->assertNull((new BumpSequenceResult())->getErrorMessage());
        $this->assertNull((new BumpSequenceResult())->getErrorCode());
    }
}
