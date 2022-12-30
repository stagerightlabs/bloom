<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Primitives\String28;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\Memo;
use StageRightLabs\Bloom\Transaction\MemoType;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\Memo
 */
class MemoTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(MemoType::class, Memo::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            MemoType::MEMO_NONE   => XDR::VOID,
            MemoType::MEMO_TEXT   => String28::class,
            MemoType::MEMO_ID     => UInt64::class,
            MemoType::MEMO_HASH   => Hash::class,
            MemoType::MEMO_RETURN => Hash::class,
        ];

        $this->assertEquals($expected, Memo::arms());
    }

    /**
     * @test
     * @covers ::none
     */
    public function it_can_be_instantiated_as_an_empty_memo()
    {
        $memo = Memo::none();
        $this->assertNull($memo->unwrap());
    }

    /**
     * @test
     * @covers ::wrapText
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_a_text_memo()
    {
        $memo = Memo::wrapText('Hello World');
        $this->assertEquals('Hello World', $memo->unwrap());
    }

    /**
     * @test
     * @covers ::wrapId
     */
    public function it_can_be_instantiated_from_an_id()
    {
        $memo = Memo::wrapId(UInt64::of(1));
        $this->assertTrue($memo->unwrap()->isEqualTo(1));
    }

    /**
     * @test
     * @covers ::wrapHash
     */
    public function it_can_be_instantiated_from_a_32_byte_hash_string()
    {
        $memo = Memo::wrapHash(Hash::make('abcdefghijklmnopqrstuvwxyz123456'));
        $this->assertEquals('f6d527e6d01865481134f29788be2afe7fc3c702e1a55d7ceafac5f35199e8dc', $memo->unwrap()->toHex());
    }

    /**
     * @test
     * @covers ::wrapReturn
     */
    public function it_can_be_instantiated_from_a_32_byte_refunded_transaction_hash()
    {
        $memo = Memo::wrapReturn(Hash::make('abcdefghijklmnopqrstuvwxyz123456'));
        $this->assertEquals('f6d527e6d01865481134f29788be2afe7fc3c702e1a55d7ceafac5f35199e8dc', $memo->unwrap()->toHex());
    }
}
