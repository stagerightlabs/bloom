<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\Liabilities;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\TrustLineEntryV1;
use StageRightLabs\Bloom\Ledger\TrustLineEntryV1Ext;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\TrustLineEntryV1
 */
class TrustLineEntryV1Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $liabilities = (new Liabilities())
            ->withBuying(Int64::of(1))
            ->withSelling(Int64::of(2));
        $trustLineEntryV1 = (new TrustLineEntryV1())->withLiabilities($liabilities);
        $buffer = XDR::fresh()->write($trustLineEntryV1);

        $this->assertEquals('AAAAAAAAAAEAAAAAAAAAAgAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function liabilities_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new TrustLineEntryV1());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $trustLineEntryV1 = XDR::fromBase64('AAAAAAAAAAEAAAAAAAAAAgAAAAA=')
            ->read(TrustLineEntryV1::class);

        $this->assertInstanceOf(TrustLineEntryV1::class, $trustLineEntryV1);
        $this->assertInstanceOf(Liabilities::class, $trustLineEntryV1->getLiabilities());
        $this->assertInstanceOf(TrustLineEntryV1Ext::class, $trustLineEntryV1->getExtension());
    }

    /**
     * @test
     * @covers ::withLiabilities
     * @covers ::getLiabilities
     */
    public function it_accepts_liabilities()
    {
        $liabilities = (new Liabilities())
            ->withBuying(Int64::of(1))
            ->withSelling(Int64::of(2));
        $trustLineEntryV1 = (new TrustLineEntryV1())->withLiabilities($liabilities);

        $this->assertInstanceOf(TrustLineEntryV1::class, $trustLineEntryV1);
        $this->assertInstanceOf(Liabilities::class, $trustLineEntryV1->getLiabilities());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $trustLineEntryV1 = (new TrustLineEntryV1())->withExtension(TrustLineEntryV1Ext::empty());

        $this->assertInstanceOf(TrustLineEntryV1::class, $trustLineEntryV1);
        $this->assertInstanceOf(TrustLineEntryV1Ext::class, $trustLineEntryV1->getExtension());
    }
}
