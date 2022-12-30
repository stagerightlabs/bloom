<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\ManageDataOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Primitives\OptionalDataValue;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageDataOp
 */
class ManageDataOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = ManageDataOp::operation(
            name: 'FOO',
            value: 'BAR',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ManageDataOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $manageDataOp = new ManageDataOp();
        $this->assertFalse($manageDataOp->isReady());

        $manageDataOp = $manageDataOp->withDataName('FOO');
        $this->assertTrue($manageDataOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new ManageDataOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $manageDataOp = (new ManageDataOp())->withDataName(String64::of('example'));
        $buffer = XDR::fresh()->write($manageDataOp);

        $this->assertEquals('AAAAB2V4YW1wbGUAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_data_name_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new ManageDataOp());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $manageDataOp = XDR::fromBase64('AAAAB2V4YW1wbGUAAAAAAA==')
            ->read(ManageDataOp::class);

        $this->assertInstanceOf(ManageDataOp::class, $manageDataOp);
        $this->assertInstanceOf(String64::class, $manageDataOp->getDataName());
        $this->assertNull($manageDataOp->getDataValue());
    }

    /**
     * @test
     * @covers ::withDataName
     * @covers ::getDataName
     */
    public function it_accepts_a_string_64_as_a_data_name()
    {
        $manageDataOp = (new ManageDataOp())->withDataName(String64::of('example'));

        $this->assertInstanceOf(ManageDataOp::class, $manageDataOp);
        $this->assertInstanceOf(String64::class, $manageDataOp->getDataName());
    }

    /**
     * @test
     * @covers ::withDataName
     * @covers ::getDataName
     */
    public function it_accepts_a_native_string_as_a_data_name()
    {
        $manageDataOp = (new ManageDataOp())->withDataName('example');

        $this->assertInstanceOf(ManageDataOp::class, $manageDataOp);
        $this->assertInstanceOf(String64::class, $manageDataOp->getDataName());
    }

    /**
     * @test
     * @covers ::withDataValue
     * @covers ::getDataValue
     */
    public function it_accepts_a_data_value_as_a_data_value()
    {
        $manageDataOp = (new ManageDataOp())->withDataValue(new DataValue());

        $this->assertInstanceOf(ManageDataOp::class, $manageDataOp);
        $this->assertInstanceOf(DataValue::class, $manageDataOp->getDataValue());
    }

    /**
     * @test
     * @covers ::withDataValue
     * @covers ::getDataValue
     */
    public function it_accepts_an_optional_data_value_as_a_data_value()
    {
        $manageDataOp = (new ManageDataOp())->withDataValue(new OptionalDataValue());

        $this->assertInstanceOf(ManageDataOp::class, $manageDataOp);
        $this->assertNull($manageDataOp->getDataValue());
    }

    /**
     * @test
     * @covers ::withDataValue
     * @covers ::getDataValue
     */
    public function it_accepts_an_null_as_a_data_value()
    {
        $manageDataOp = (new ManageDataOp())->withDataValue();

        $this->assertInstanceOf(ManageDataOp::class, $manageDataOp);
        $this->assertNull($manageDataOp->getDataValue());
    }
}
