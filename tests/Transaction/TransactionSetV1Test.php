<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionPhaseList;
use StageRightLabs\Bloom\Transaction\TransactionSetV1;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionSetV1
 */
class TransactionSetV1Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $transactionSetV1 = (new TransactionSetV1())
            ->withPreviousLedgerHash(Hash::make('1'));
        $buffer = XDR::fresh()->write($transactionSetV1);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_previous_leger_hash_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new TransactionSetV1());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionSetV1 = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAA')
            ->read(TransactionSetV1::class);

        $this->assertInstanceOf(TransactionSetV1::class, $transactionSetV1);
        $this->assertInstanceOf(Hash::class, $transactionSetV1->getPreviousLedgerHash());
        $this->assertInstanceOf(TransactionPhaseList::class, $transactionSetV1->getPhases());
    }

    /**
     * @test
     * @covers ::withPreviousLedgerHash
     * @covers ::getPreviousLedgerHash
     */
    public function it_accepts_a_previous_leger_hash()
    {
        $transactionSetV1 = (new TransactionSetV1())
            ->withPreviousLedgerHash(Hash::make('1'));

        $this->assertInstanceOf(TransactionSetV1::class, $transactionSetV1);
        $this->assertInstanceOf(Hash::class, $transactionSetV1->getPreviousLedgerHash());
    }

    /**
     * @test
     * @covers ::withPhases
     * @covers ::getPhases
     */
    public function it_accepts_a_list_of_transaction_phases()
    {
        $transactionSetV1 = (new TransactionSetV1())
            ->withPhases(TransactionPhaseList::empty());

        $this->assertInstanceOf(TransactionSetV1::class, $transactionSetV1);
        $this->assertInstanceOf(TransactionPhaseList::class, $transactionSetV1->getPhases());
    }
}
