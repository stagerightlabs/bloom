<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Operation\EndSponsoringFutureReservesOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\EndSponsoringFutureReservesOp
 */
class EndSponsoringFutureReservesOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = EndSponsoringFutureReservesOp::operation(
            source: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertNull($operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $this->assertTrue((new EndSponsoringFutureReservesOp())->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new EndSponsoringFutureReservesOp())->getThreshold());
    }
}
