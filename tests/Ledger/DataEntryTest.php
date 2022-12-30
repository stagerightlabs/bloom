<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\DataEntry;
use StageRightLabs\Bloom\Ledger\DataEntryExt;
use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\DataEntry
 */
class DataEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('name'))
            ->withDataValue(DataValue::of('value'));
        $buffer = XDR::fresh()->write($dataEntry);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAABG5hbWUAAAAFdmFsdWUAAAAAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_account_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $dataEntry = (new DataEntry())
            ->withDataName(String64::of('name'))
            ->withDataValue(DataValue::of('value'));
        XDR::fresh()->write($dataEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_data_name_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataValue(DataValue::of('value'));
        XDR::fresh()->write($dataEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_data_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('name'));
        XDR::fresh()->write($dataEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $dataEntry = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAABG5hbWUAAAAFdmFsdWUAAAAAAAAA')
            ->read(DataEntry::class);

        $this->assertInstanceOf(DataEntry::class, $dataEntry);
        $this->assertInstanceOf(AccountId::class, $dataEntry->getAccountId());
        $this->assertInstanceOf(String64::class, $dataEntry->getDataName());
        $this->assertEquals('name', $dataEntry->getDataName()->toNativeString());
        $this->assertInstanceOf(DataValue::class, $dataEntry->getDataValue());
        $this->assertEquals('value', $dataEntry->getDataValue()->toNativeString());
        $this->assertInstanceOf(DataEntryExt::class, $dataEntry->getExtension());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_account_id()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(DataEntry::class, $dataEntry);
        $this->assertInstanceOf(AccountId::class, $dataEntry->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_a_string_as_an_account_id()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(DataEntry::class, $dataEntry);
        $this->assertInstanceOf(AccountId::class, $dataEntry->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_addressable_as_an_account_id()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(DataEntry::class, $dataEntry);
        $this->assertInstanceOf(AccountId::class, $dataEntry->getAccountId());
    }

    /**
     * @test
     * @covers ::withDataName
     * @covers ::getDataName
     */
    public function it_accepts_a_string64_as_a_data_name()
    {
        $dataEntry = (new DataEntry())->withDataName(String64::of('name'));

        $this->assertInstanceOf(DataEntry::class, $dataEntry);
        $this->assertInstanceOf(String64::class, $dataEntry->getDataName());
    }

    /**
     * @test
     * @covers ::withDataName
     * @covers ::getDataName
     */
    public function it_accepts_a_native_string_as_a_data_name()
    {
        $dataEntry = (new DataEntry())->withDataName('name');

        $this->assertInstanceOf(DataEntry::class, $dataEntry);
        $this->assertInstanceOf(String64::class, $dataEntry->getDataName());
    }

    /**
     * @test
     * @covers ::withDataValue
     * @covers ::getDataValue
     */
    public function it_accepts_a_data_value()
    {
        $dataEntry = (new DataEntry())->withDataValue(DataValue::of('value'));

        $this->assertInstanceOf(DataEntry::class, $dataEntry);
        $this->assertInstanceOf(DataValue::class, $dataEntry->getDataValue());
    }

    /**
     * @test
     * @covers ::withDataValue
     * @covers ::getDataValue
     */
    public function it_accepts_a_native_string_as_a_data_value()
    {
        $dataEntry = (new DataEntry())->withDataValue('value');

        $this->assertInstanceOf(DataEntry::class, $dataEntry);
        $this->assertInstanceOf(DataValue::class, $dataEntry->getDataValue());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $dataEntry = (new DataEntry())->withExtension(DataEntryExt::empty());

        $this->assertInstanceOf(DataEntry::class, $dataEntry);
        $this->assertInstanceOf(DataEntryExt::class, $dataEntry->getExtension());
    }
}
