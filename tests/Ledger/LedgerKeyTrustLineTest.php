<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\TrustLineAsset;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerKeyTrustLine;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerKeyTrustLine
 */
class LedgerKeyTrustLineTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withAsset(TrustLineAsset::native());
        $buffer = XDR::fresh()->write($ledgerKeyTrustLine);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_account_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAsset(TrustLineAsset::native());
        XDR::fresh()->write($ledgerKeyTrustLine);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_trust_line_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));
        XDR::fresh()->write($ledgerKeyTrustLine);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerKeyTrustLine = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAA==')
            ->read(LedgerKeyTrustLine::class);

        $this->assertInstanceOf(LedgerKeyTrustLine::class, $ledgerKeyTrustLine);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyTrustLine->getAccountId());
        $this->assertInstanceOf(TrustLineAsset::class, $ledgerKeyTrustLine->getAsset());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_account_id()
    {
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(LedgerKeyTrustLine::class, $ledgerKeyTrustLine);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyTrustLine->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_a_string_as_an_account_id()
    {
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(LedgerKeyTrustLine::class, $ledgerKeyTrustLine);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyTrustLine->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_addressable_as_an_account_id()
    {
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAccountId(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(LedgerKeyTrustLine::class, $ledgerKeyTrustLine);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyTrustLine->getAccountId());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_a_trust_line_asset()
    {
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAsset(TrustLineAsset::native());

        $this->assertInstanceOf(LedgerKeyTrustLine::class, $ledgerKeyTrustLine);
        $this->assertInstanceOf(TrustLineAsset::class, $ledgerKeyTrustLine->getAsset());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_an_asset_as_a_trust_line_asset()
    {
        $asset = Asset::fromNativeString('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAsset($asset);

        $this->assertInstanceOf(LedgerKeyTrustLine::class, $ledgerKeyTrustLine);
        $this->assertInstanceOf(TrustLineAsset::class, $ledgerKeyTrustLine->getAsset());
    }
}
