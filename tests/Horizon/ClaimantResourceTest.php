<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\ClaimantResource;
use StageRightLabs\Bloom\Horizon\PredicateResource;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\ClaimantResource
 */
class ClaimantResourceTest extends TestCase
{
    public const EXAMPLE = [
        'destination' => 'GC3C4AKRBQLHOJ45U4XG35ESVWRDECWO5XLDGYADO6DPR3L7KIDVUMML',
        'predicate'   => [
            'and' => [
                [
                    'or' => [
                        [
                            'relBefore' => '12'
                        ],
                        [
                            'absBefore'      => '2020-08-26T11:15:39Z',
                            'absBeforeEpoch' => '1598440539'
                        ]
                    ]
                ],
                [
                    'not' => [
                        'unconditional' => true,
                    ]
                ]
            ]
        ]
    ];

    /**
     * @test
     * @covers ::getDestination
     */
    public function it_returns_the_destination()
    {
        $claimant = ClaimantResource::wrap(self::EXAMPLE);

        $this->assertEquals(
            'GC3C4AKRBQLHOJ45U4XG35ESVWRDECWO5XLDGYADO6DPR3L7KIDVUMML',
            $claimant->getDestination()
        );
    }

    /**
     * @test
     * @covers ::getPredicate
     */
    public function it_returns_the_predicate()
    {
        $claimant = ClaimantResource::wrap(self::EXAMPLE);

        $this->assertInstanceOf(PredicateResource::class, $claimant->getPredicate());
        $this->assertNull((new ClaimantResource())->getPredicate());
    }
}
