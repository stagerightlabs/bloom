<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Envelope\TransactionEnvelopeList;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionSet;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionSet
 */
class TransactionSetTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $transactionSet = (new TransactionSet())
            ->withPreviousLedgerHash(Hash::make('1'));
        $buffer = XDR::fresh()->write($transactionSet);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_previous_ledger_hash_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new TransactionSet());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionSet = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAA')
            ->read(TransactionSet::class);

        $this->assertInstanceOf(TransactionSet::class, $transactionSet);
        $this->assertInstanceOf(Hash::class, $transactionSet->getPreviousLedgerHash());
        $this->assertInstanceOf(TransactionEnvelopeList::class, $transactionSet->getTransactions());
    }

    /**
     * @test
     * @covers ::withPreviousLedgerHash
     * @covers ::getPreviousLedgerHash
     */
    public function it_accepts_a_previous_ledger_hash()
    {
        $transactionSet = (new TransactionSet())
            ->withPreviousLedgerHash(Hash::make('1'));

        $this->assertInstanceOf(TransactionSet::class, $transactionSet);
        $this->assertInstanceOf(Hash::class, $transactionSet->getPreviousLedgerHash());
    }

    /**
     * @test
     * @covers ::withTransactions
     * @covers ::getTransactions
     */
    public function it_accepts_a_list_of_transactions()
    {
        $transactionSet = (new TransactionSet())
            ->withTransactions(TransactionEnvelopeList::empty());

        $this->assertInstanceOf(TransactionSet::class, $transactionSet);
        $this->assertInstanceOf(TransactionEnvelopeList::class, $transactionSet->getTransactions());
    }
}
