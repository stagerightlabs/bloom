<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\PredicateResource;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\PredicateResource
 */
class PredicateResourceTest extends TestCase
{
    public const EXAMPLE = [
        'and' => [
            [
                'or' => [
                    [
                        'rel_before' => '12'
                    ],
                    [
                        'abs_before'       => '2020-08-26T11:15:39Z',
                        'abs_before_epoch' => '1598440539'
                    ]
                ]
            ],
            [
                'not' => [
                    'unconditional' => true,
                ]
            ]
        ]
    ];

    /**
     * @test
     * @covers ::isUnconditional
     */
    public function it_knows_if_it_is_unconditional()
    {
        $predicateA = PredicateResource::wrap(self::EXAMPLE);
        $predicateB = PredicateResource::wrap(['unconditional' => true]);

        $this->assertFalse($predicateA->isUnconditional());
        $this->assertTrue($predicateB->isUnconditional());
    }

    /**
     * @test
     * @covers ::getAndPredicates
     */
    public function it_returns_the_and_predicates()
    {
        $predicate = PredicateResource::wrap(self::EXAMPLE);

        foreach ($predicate->getAndPredicates() as $p) {
            $this->assertInstanceOf(PredicateResource::class, $p);
        }
        $this->assertEmpty((new PredicateResource())->getAndPredicates());
    }

    /**
     * @test
     * @covers ::getOrPredicates
     */
    public function it_returns_the_or_predicates()
    {
        $predicate = PredicateResource::wrap(self::EXAMPLE);

        foreach ($predicate->getAndPredicates()[0]->getOrPredicates() as $p) {
            $this->assertInstanceOf(PredicateResource::class, $p);
        }
        $this->assertEmpty((new PredicateResource())->getOrPredicates());
    }

    /**
     * @test
     * @covers ::getNotPredicate
     */
    public function it_returns_the_not_predicate()
    {
        $predicate = PredicateResource::wrap(self::EXAMPLE);

        $this->assertInstanceOf(
            PredicateResource::class,
            $predicate->getAndPredicates()[1]->getNotPredicate()
        );
        $this->assertNull((new PredicateResource())->getNotPredicate());
    }

    /**
     * @test
     * @covers ::getAbsoluteBeforeTimestamp
     * @covers ::getAbsoluteBeforeEpoch
     */
    public function it_returns_the_absolute_timestamp_and_epoch()
    {
        $predicate = PredicateResource::wrap(self::EXAMPLE);
        $child = $predicate->getAndPredicates()[0]->getOrPredicates()[1];

        $this->assertEquals('2020-08-26T11:15:39Z', $child->getAbsoluteBeforeTimestamp());
        $this->assertEquals('1598440539', $child->getAbsoluteBeforeEpoch());
    }

    /**
     * @test
     * @covers ::getRelativeBeforeSeconds
     */
    public function it_returns_the_relative_before_seconds()
    {
        $predicate = PredicateResource::wrap(self::EXAMPLE);
        $child = $predicate->getAndPredicates()[0]->getOrPredicates()[0];

        $this->assertEquals('12', $child->getRelativeBeforeSeconds());
    }
}
