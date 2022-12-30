<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\OfferEntryFlags;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\OfferEntryFlags
 */
class OfferEntryFlagsTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            1 => OfferEntryFlags::PASSIVE_FLAG,
        ];
        $offerEntryFlags = new OfferEntryFlags();

        $this->assertEquals($expected, $offerEntryFlags->getOptions());
    }

    /**
     * @test
     * @covers ::toNativeInt
     */
    public function it_returns_an_integer_representation_of_the_selected_flag()
    {
        $this->assertEquals(1, OfferEntryFlags::passive()->toNativeInt());
    }

    /**
     * @test
     * @covers ::passive
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_passive_type()
    {
        $offerEntryFlags = OfferEntryFlags::passive();

        $this->assertInstanceOf(OfferEntryFlags::class, $offerEntryFlags);
        $this->assertEquals(OfferEntryFlags::PASSIVE_FLAG, $offerEntryFlags->getType());
    }
}
