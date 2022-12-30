<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ManageOfferEffect;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageOfferEffect
 */
class ManageOfferEffectTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => ManageOfferEffect::MANAGE_OFFER_CREATED,
            1 => ManageOfferEffect::MANAGE_OFFER_UPDATED,
            2 => ManageOfferEffect::MANAGE_OFFER_DELETED,
        ];
        $manageOfferEffect = new ManageOfferEffect();

        $this->assertEquals($expected, $manageOfferEffect->getOptions());
    }

    /**
     * @test
     * @covers ::created
     * @covers ::getEffect
     */
    public function it_can_be_instantiated_as_a_created_type()
    {
        $manageOfferEffect = ManageOfferEffect::created();

        $this->assertInstanceOf(ManageOfferEffect::class, $manageOfferEffect);
        $this->assertEquals(ManageOfferEffect::MANAGE_OFFER_CREATED, $manageOfferEffect->getEffect());
    }

    /**
     * @test
     * @covers ::updated
     * @covers ::getEffect
     */
    public function it_can_be_instantiated_as_an_updated_type()
    {
        $manageOfferEffect = ManageOfferEffect::updated();

        $this->assertInstanceOf(ManageOfferEffect::class, $manageOfferEffect);
        $this->assertEquals(ManageOfferEffect::MANAGE_OFFER_UPDATED, $manageOfferEffect->getEffect());
    }

    /**
     * @test
     * @covers ::deleted
     * @covers ::getEffect
     */
    public function it_can_be_instantiated_as_a_deleted_type()
    {
        $manageOfferEffect = ManageOfferEffect::deleted();

        $this->assertInstanceOf(ManageOfferEffect::class, $manageOfferEffect);
        $this->assertEquals(ManageOfferEffect::MANAGE_OFFER_DELETED, $manageOfferEffect->getEffect());
    }
}
