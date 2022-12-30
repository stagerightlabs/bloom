<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\BumpSequenceResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\BumpSequenceResultCode
 */
class BumpSequenceResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => BumpSequenceResultCode::BUMP_SEQUENCE_SUCCESS,
            -1 => BumpSequenceResultCode::BUMP_SEQUENCE_BAD_SEQ,
        ];
        $bumpSequenceResultCode = new BumpSequenceResultCode();

        $this->assertEquals($expected, $bumpSequenceResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $bumpSequenceResultCode = BumpSequenceResultCode::success();

        $this->assertInstanceOf(BumpSequenceResultCode::class, $bumpSequenceResultCode);
        $this->assertEquals(BumpSequenceResultCode::BUMP_SEQUENCE_SUCCESS, $bumpSequenceResultCode->getType());
    }

    /**
     * @test
     * @covers ::badSequenceNumber
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_bad_sequence_number_type()
    {
        $bumpSequenceResultCode = BumpSequenceResultCode::badSequenceNumber();

        $this->assertInstanceOf(BumpSequenceResultCode::class, $bumpSequenceResultCode);
        $this->assertEquals(BumpSequenceResultCode::BUMP_SEQUENCE_BAD_SEQ, $bumpSequenceResultCode->getType());
    }
}
