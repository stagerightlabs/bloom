<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\ReservesResource;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\ReservesResource
 */
class ReservesResourceTest extends TestCase
{
    public const EXAMPLE = [
        'asset'  => 'JPY:GBVAOIACNSB7OVUXJYC5UE2D4YK2F7A24T7EE5YOMN4CE6GCHUTOUQXM',
        'amount' => '1000.0000005',
    ];

    /**
     * @test
     * @covers ::getAsset
     */
    public function it_returns_the_asset_identifier()
    {
        $reserve = ReservesResource::wrap(self::EXAMPLE);

        $this->assertEquals(
            'JPY:GBVAOIACNSB7OVUXJYC5UE2D4YK2F7A24T7EE5YOMN4CE6GCHUTOUQXM',
            $reserve->getAsset()
        );
    }

    /**
     * @test
     * @covers ::getAmount
     */
    public function it_returns_the_asset_amount()
    {
        $reserve = ReservesResource::wrap(self::EXAMPLE);

        $this->assertEquals('1000.0000005', $reserve->getAmount()->toNativeString());
        $this->assertNull((new ReservesResource())->getAmount());
    }
}
