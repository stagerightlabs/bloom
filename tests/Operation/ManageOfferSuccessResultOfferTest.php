<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Ledger\OfferEntry;
use StageRightLabs\Bloom\Operation\ManageOfferEffect;
use StageRightLabs\Bloom\Operation\ManageOfferSuccessResultOffer;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageOfferSuccessResultOffer
 */
class ManageOfferSuccessResultOfferTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ManageOfferEffect::class, ManageOfferSuccessResultOffer::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ManageOfferEffect::MANAGE_OFFER_CREATED => OfferEntry::class,
            ManageOfferEffect::MANAGE_OFFER_UPDATED => OfferEntry::class,
            ManageOfferEffect::MANAGE_OFFER_DELETED => XDR::VOID,
        ];

        $this->assertEquals($expected, ManageOfferSuccessResultOffer::arms());
    }

    /**
     * @test
     * @covers ::wrapCreatedOffer
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_created_offer_entry()
    {
        $manageOfferSuccessResultOffer = ManageOfferSuccessResultOffer::wrapCreatedOffer(
            new OfferEntry()
        );

        $this->assertInstanceOf(ManageOfferSuccessResultOffer::class, $manageOfferSuccessResultOffer);
        $this->assertInstanceOf(OfferEntry::class, $manageOfferSuccessResultOffer->unwrap());
        $this->assertEquals(ManageOfferEffect::MANAGE_OFFER_CREATED, $manageOfferSuccessResultOffer->getType());
    }

    /**
     * @test
     * @covers ::wrapUpdatedOffer
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_updated_offer_entry()
    {
        $manageOfferSuccessResultOffer = ManageOfferSuccessResultOffer::wrapUpdatedOffer(
            new OfferEntry()
        );

        $this->assertInstanceOf(ManageOfferSuccessResultOffer::class, $manageOfferSuccessResultOffer);
        $this->assertInstanceOf(OfferEntry::class, $manageOfferSuccessResultOffer->unwrap());
        $this->assertEquals(ManageOfferEffect::MANAGE_OFFER_UPDATED, $manageOfferSuccessResultOffer->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_a_null_type_when_no_value_is_set()
    {
        $this->assertNull((new ManageOfferSuccessResultOffer())->getType());
    }
}
