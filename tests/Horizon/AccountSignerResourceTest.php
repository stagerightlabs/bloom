<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\AccountSignerResource;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\AccountSignerResource
 */
class AccountSignerResourceTest extends TestCase
{
    public const SIGNER_EXAMPLE = [
        'weight'  => 10,
        'key'     => 'GDI73WJ4SX7LOG3XZDJC3KCK6ED6E5NBYK2JUBQSPBCNNWEG3ZN7T75U',
        'sponsor' => 'GDI73WJ4SX7LOG3XZDJC3KCK6ED6E5NBYK2JUBQSPBCNNWEG3ZN7T75U',
        'type'    => 'ed25519_public_key',
    ];

    /**
     * @test
     * @covers ::getWeight
     */
    public function it_returns_the_signer_weight()
    {
        $resource = AccountSignerResource::wrap(self::SIGNER_EXAMPLE);
        $this->assertEquals(10, $resource->getWeight()->toNativeInt());

        $resource = AccountSignerResource::wrap([]);
        $this->assertEquals(0, $resource->getWeight()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getSponsor
     */
    public function it_returns_the_sponsor_address_if_present()
    {
        $resource = AccountSignerResource::wrap(self::SIGNER_EXAMPLE);
        $this->assertEquals(
            'GDI73WJ4SX7LOG3XZDJC3KCK6ED6E5NBYK2JUBQSPBCNNWEG3ZN7T75U',
            $resource->getSponsor()
        );

        $resource = AccountSignerResource::wrap([]);
        $this->assertNull($resource->getSponsor());
    }

    /**
     * @test
     * @covers ::getKey
     */
    public function it_returns_the_key_if_present()
    {
        $resource = AccountSignerResource::wrap(self::SIGNER_EXAMPLE);
        $this->assertEquals(
            'GDI73WJ4SX7LOG3XZDJC3KCK6ED6E5NBYK2JUBQSPBCNNWEG3ZN7T75U',
            $resource->getKey()
        );

        $resource = AccountSignerResource::wrap([]);
        $this->assertNull($resource->getKey());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_signer_type_if_present()
    {
        $resource = AccountSignerResource::wrap(self::SIGNER_EXAMPLE);
        $this->assertEquals('ed25519_public_key', $resource->getType());

        $resource = AccountSignerResource::wrap([]);
        $this->assertNull($resource->getType());
    }
}
