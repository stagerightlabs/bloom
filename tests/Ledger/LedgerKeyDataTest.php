<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerKeyData;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerKeyData
 */
class LedgerKeyDataTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerKeyData = (new LedgerKeyData())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('example'));
        $buffer = XDR::fresh()->write($ledgerKeyData);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAB2V4YW1wbGUA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_account_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerKeyData = (new LedgerKeyData())
            ->withDataName(String64::of('example'));
        XDR::fresh()->write($ledgerKeyData);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_data_name_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerKeyData = (new LedgerKeyData())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));
        XDR::fresh()->write($ledgerKeyData);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerKeyData = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAB2V4YW1wbGUA')
            ->read(LedgerKeyData::class);

        $this->assertInstanceOf(LedgerKeyData::class, $ledgerKeyData);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyData->getAccountId());
        $this->assertInstanceOf(String64::class, $ledgerKeyData->getDataName());
        $this->assertEquals('example', $ledgerKeyData->getDataName()->toNativeString());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_account_id()
    {
        $ledgerKeyData = (new LedgerKeyData())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(LedgerKeyData::class, $ledgerKeyData);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyData->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_a_string_as_an_account_id()
    {
        $ledgerKeyData = (new LedgerKeyData())
            ->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(LedgerKeyData::class, $ledgerKeyData);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyData->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_addressable_as_an_account_id()
    {
        $ledgerKeyData = (new LedgerKeyData())
            ->withAccountId(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(LedgerKeyData::class, $ledgerKeyData);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyData->getAccountId());
    }

    /**
     * @test
     * @covers ::withDataName
     * @covers ::getDataName
     */
    public function it_accepts_a_data_name()
    {
        $ledgerKeyData = (new LedgerKeyData())
            ->withDataName(String64::of('example'));

        $this->assertInstanceOf(LedgerKeyData::class, $ledgerKeyData);
        $this->assertInstanceOf(String64::class, $ledgerKeyData->getDataName());
        $this->assertEquals('example', $ledgerKeyData->getDataName()->toNativeString());
    }

    /**
     * @test
     * @covers ::withDataName
     * @covers ::getDataName
     */
    public function it_accepts_a_native_strign_as_a_data_name()
    {
        $ledgerKeyData = (new LedgerKeyData())
            ->withDataName('example');

        $this->assertInstanceOf(LedgerKeyData::class, $ledgerKeyData);
        $this->assertInstanceOf(String64::class, $ledgerKeyData->getDataName());
        $this->assertEquals('example', $ledgerKeyData->getDataName()->toNativeString());
    }
}
