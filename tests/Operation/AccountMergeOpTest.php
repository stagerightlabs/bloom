<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Operation\AccountMergeOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\AccountMergeOp
 */
class AccountMergeOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = AccountMergeOp::operation(
            destination: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            source: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(MuxedAccount::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $this->assertTrue((new AccountMergeOp())->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_HIGH, (new AccountMergeOp())->getThreshold());
    }
}
