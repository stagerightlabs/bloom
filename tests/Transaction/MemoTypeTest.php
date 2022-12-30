<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\MemoType;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\MemoType
 */
class MemoTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => MemoType::MEMO_NONE,
            1 => MemoType::MEMO_TEXT,
            2 => MemoType::MEMO_ID,
            3 => MemoType::MEMO_HASH,
            4 => MemoType::MEMO_RETURN,
        ];
        $memoType = new MemoType();

        $this->assertEquals($expected, $memoType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $memoType = MemoType::none();
        $this->assertEquals(MemoType::MEMO_NONE, $memoType->getType());
    }

    /**
     * @test
     * @covers ::getDefaultSelection
     */
    public function it_has_a_default_selection()
    {
        $memoType = new MemoType();
        $this->assertEquals(MemoType::MEMO_NONE, $memoType->getType());
    }

    /**
     * @test
     * @covers ::none
     */
    public function it_can_be_instantiated_as_an_empty_memo_type()
    {
        $memoType = MemoType::none();

        $this->assertInstanceOf(MemoType::class, $memoType);
        $this->assertEquals(MemoType::MEMO_NONE, $memoType->getType());
    }

    /**
     * @test
     * @covers ::text
     */
    public function it_can_be_instantiated_as_a_text_memo_type()
    {
        $memoType = MemoType::text();

        $this->assertInstanceOf(MemoType::class, $memoType);
        $this->assertEquals(MemoType::MEMO_TEXT, $memoType->getType());
    }

    /**
     * @test
     * @covers ::id
     */
    public function it_can_be_instantiated_as_an_id_memo_type()
    {
        $memoType = MemoType::id();

        $this->assertInstanceOf(MemoType::class, $memoType);
        $this->assertEquals(MemoType::MEMO_ID, $memoType->getType());
    }

    /**
     * @test
     * @covers ::hash
     */
    public function it_can_be_instantiated_as_a_hash_memo_type()
    {
        $memoType = MemoType::hash();

        $this->assertInstanceOf(MemoType::class, $memoType);
        $this->assertEquals(MemoType::MEMO_HASH, $memoType->getType());
    }

    /**
     * @test
     * @covers ::return
     */
    public function it_can_be_instantiated_as_a_return_memo_type()
    {
        $memoType = MemoType::return();

        $this->assertInstanceOf(MemoType::class, $memoType);
        $this->assertEquals(MemoType::MEMO_RETURN, $memoType->getType());
    }
}
