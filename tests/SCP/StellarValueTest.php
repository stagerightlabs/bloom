<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\SCP\StellarValue;
use StageRightLabs\Bloom\SCP\StellarValueExt;
use StageRightLabs\Bloom\SCP\UpgradeTypeList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\StellarValue
 */
class StellarValueTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $stellarValue = (new StellarValue())
            ->withTxSetHash(Hash::make('1'))
            ->withCloseTime(TimePoint::fromNativeString('2022-01-01'));
        $buffer = XDR::fresh()->write($stellarValue);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAYc+ZgAAAAAAAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_tx_set_hash_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $stellarValue = (new StellarValue())
            ->withCloseTime(TimePoint::fromNativeString('2022-01-01'));
        XDR::fresh()->write($stellarValue);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_close_time_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $stellarValue = (new StellarValue())
            ->withTxSetHash(Hash::make('1'));
        XDR::fresh()->write($stellarValue);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $stellarValue = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAYc+ZgAAAAAAAAAAA')
            ->read(StellarValue::class);

        $this->assertInstanceOf(StellarValue::class, $stellarValue);
        $this->assertInstanceOf(Hash::class, $stellarValue->getTxSetHash());
        $this->assertInstanceOf(TimePoint::class, $stellarValue->getCloseTime());
        $this->assertInstanceOf(UpgradeTypeList::class, $stellarValue->getUpgrades());
        $this->assertInstanceOf(StellarValueExt::class, $stellarValue->getExtension());
    }

    /**
     * @test
     * @covers ::withTxSetHash
     * @covers ::getTxSetHash
     */
    public function it_accepts_a_tx_set_hash()
    {
        $stellarValue = (new StellarValue())->withTxSetHash(Hash::make('1'));

        $this->assertInstanceOf(StellarValue::class, $stellarValue);
        $this->assertInstanceOf(Hash::class, $stellarValue->getTxSetHash());
    }

    /**
     * @test
     * @covers ::withCloseTime
     * @covers ::getCloseTime
     */
    public function it_accepts_a_close_time()
    {
        $stellarValue = (new StellarValue())
            ->withCloseTime(TimePoint::fromNativeString('2022-01-01'));

        $this->assertInstanceOf(StellarValue::class, $stellarValue);
        $this->assertInstanceOf(TimePoint::class, $stellarValue->getCloseTime());
    }

    /**
     * @test
     * @covers ::withUpgrades
     * @covers ::getUpgrades
     */
    public function it_accepts_a_list_of_upgrades()
    {
        $stellarValue = (new StellarValue())
            ->withUpgrades(UpgradeTypeList::empty());

        $this->assertInstanceOf(StellarValue::class, $stellarValue);
        $this->assertInstanceOf(UpgradeTypeList::class, $stellarValue->getUpgrades());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $stellarValue = (new StellarValue())
            ->withExtension(StellarValueExt::basic());

        $this->assertInstanceOf(StellarValue::class, $stellarValue);
        $this->assertInstanceOf(StellarValueExt::class, $stellarValue->getExtension());
    }
}
