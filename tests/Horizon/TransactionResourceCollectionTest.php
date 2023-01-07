<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Horizon\TransactionResource;
use StageRightLabs\Bloom\Horizon\TransactionResourceCollection;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\TransactionResourceCollection
 */
class TransactionResourceCollectionTest extends TestCase
{
    /**
     * @test
     * @covers ::getResourceClass
     */
    public function it_defines_an_underlying_resource_class()
    {
        $collection = TransactionResourceCollection::fromResponse(Response::fake('account_transactions'));

        foreach ($collection as $r) {
            $this->assertInstanceOf(TransactionResource::class, $r);
        }
    }

    /**
     * @test
     * @covers ::getNextLink
     * @covers ::getNextPagingToken
     */
    public function it_returns_the_link_to_the_next_page_of_transactions()
    {
        $collection = TransactionResourceCollection::fromResponse(Response::fake('account_transactions'));
        $this->assertEquals(
            'https://horizon-testnet.stellar.org/accounts/GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW/transactions?cursor=1116579827830784&limit=10&order=asc',
            $collection->getNextLink()
        );
        $this->assertEquals('1116579827830784', $collection->getNextPagingToken());
    }

    /**
     * @test
     * @covers ::getPreviousLink
     * @covers ::getPreviousPagingToken
     */
    public function it_returns_the_link_to_the_previous_page_of_transactions()
    {
        $collection = TransactionResourceCollection::fromResponse(Response::fake('account_transactions'));
        $this->assertEquals(
            'https://horizon-testnet.stellar.org/accounts/GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW/transactions?cursor=1115351467184128&limit=10&order=desc',
            $collection->getPreviousLink()
        );
        $this->assertEquals('1115351467184128', $collection->getPreviousPagingToken());
    }
}
