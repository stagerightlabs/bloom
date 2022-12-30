<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Envelope\TransactionEnvelopeList;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\OptionalInt64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TxSetComponentTxsMaybeDiscountedFee;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TxSetComponentTxsMaybeDiscountedFee
 */
class TxSetComponentTxsMaybeDiscountedFeeTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $buffer = XDR::fresh()->write(new TxSetComponentTxsMaybeDiscountedFee());
        $this->assertEquals('AAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $txSetComponentTxsMaybeDiscountedFee = XDR::fromBase64('AAAAAAAAAAA=')
            ->read(TxSetComponentTxsMaybeDiscountedFee::class);

        $this->assertInstanceOf(TxSetComponentTxsMaybeDiscountedFee::class, $txSetComponentTxsMaybeDiscountedFee);
        $this->assertNull($txSetComponentTxsMaybeDiscountedFee->getBaseFee());
        $this->assertInstanceOf(TransactionEnvelopeList::class, $txSetComponentTxsMaybeDiscountedFee->getTransactions());
    }

    /**
     * @test
     * @covers ::withBaseFee
     * @covers ::getBaseFee
     */
    public function it_accepts_an_int64_as_a_base_fee()
    {
        $txSetComponentTxsMaybeDiscountedFee = (new TxSetComponentTxsMaybeDiscountedFee())
            ->withBaseFee(Int64::of(1));

        $this->assertInstanceOf(TxSetComponentTxsMaybeDiscountedFee::class, $txSetComponentTxsMaybeDiscountedFee);
        $this->assertInstanceOf(Int64::class, $txSetComponentTxsMaybeDiscountedFee->getBaseFee());
    }

    /**
     * @test
     * @covers ::withBaseFee
     * @covers ::getBaseFee
     */
    public function it_accepts_an_optional_int64_as_a_base_fee()
    {
        $txSetComponentTxsMaybeDiscountedFee = (new TxSetComponentTxsMaybeDiscountedFee())
            ->withBaseFee(OptionalInt64::some(1));

        $this->assertInstanceOf(TxSetComponentTxsMaybeDiscountedFee::class, $txSetComponentTxsMaybeDiscountedFee);
        $this->assertInstanceOf(Int64::class, $txSetComponentTxsMaybeDiscountedFee->getBaseFee());
    }

    /**
     * @test
     * @covers ::withTransactions
     * @covers ::getTransactions
     */
    public function it_accepts_a_list_of_transaction_envelopes()
    {
        $txSetComponentTxsMaybeDiscountedFee = (new TxSetComponentTxsMaybeDiscountedFee())
            ->withTransactions(TransactionEnvelopeList::empty());

        $this->assertInstanceOf(TxSetComponentTxsMaybeDiscountedFee::class, $txSetComponentTxsMaybeDiscountedFee);
        $this->assertInstanceOf(TransactionEnvelopeList::class, $txSetComponentTxsMaybeDiscountedFee->getTransactions());
    }
}
