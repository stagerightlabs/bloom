<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\Resource;
use StageRightLabs\Bloom\Horizon\ResourceCollection;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\ResourceCollection
 */
class ResourceCollectionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::getResourceClass
     */
    public function it_can_be_instantiated_with_an_array_payload()
    {
        $collection = new ResourceCollection([
            '_embedded' => [
                'records' => [
                    ['foo' => 'bar'],
                    ['foo' => 'bar']
                ]
            ]
        ]);

        $this->assertInstanceOf(ResourceCollection::class, $collection);
        foreach ($collection as $resource) {
            $this->assertInstanceOf(Resource::class, $resource);
        }
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getResourceClass
     */
    public function it_can_be_instantiated_from_a_json_string()
    {
        $collection = new ResourceCollection('{"_embedded":{"records":[{"foo":"bar"},{"foo":"bar"}]}}');

        $this->assertInstanceOf(ResourceCollection::class, $collection);
        foreach ($collection as $resource) {
            $this->assertInstanceOf(Resource::class, $resource);
        }
    }

    /**
     * @test
     * @covers ::fromArray
     */
    public function it_can_be_instantiated_from_an_array()
    {
        $collection = ResourceCollection::fromArray([
            '_embedded' => [
                'records' => [
                    ['foo' => 'bar'],
                    ['foo' => 'bar']
                ]
            ]
        ]);

        $this->assertInstanceOf(ResourceCollection::class, $collection);
        foreach ($collection as $resource) {
            $this->assertInstanceOf(Resource::class, $resource);
        }
    }

    /**
     * @test
     * @covers ::fromResponse
     * @covers ::getResponse
     */
    public function it_can_be_instantiated_from_a_response()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));

        $this->assertInstanceOf(ResourceCollection::class, $collection);
        $this->assertInstanceOf(Response::class, $collection->getResponse());
    }

    /**
     * @test
     * @covers ::get
     */
    public function it_can_fetch_the_element_at_a_given_index()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));
        $this->assertInstanceOf(Resource::class, $collection->get(0));
    }

    /**
     * @test
     * @covers ::count
     */
    public function it_can_return_a_count_of_collection_members()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));
        $this->assertCount(2, $collection);
    }

    /**
     * @test
     * @covers ::current
     * @covers ::key
     * @covers ::next
     * @covers ::rewind
     * @covers ::valid
     */
    public function it_implements_the_iterator_interface()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));

        // current()
        $this->assertInstanceOf(Resource::class, $collection->current());

        // key()
        $this->assertEquals(0, $collection->key());

        // next()
        $collection->next();
        $this->assertEquals(1, $collection->key());

        // rewind()
        $collection->rewind();
        $this->assertEquals(0, $collection->key());

        // valid()
        $this->assertTrue($collection->valid());
    }

    /**
     * @test
     * @covers ::offsetExists
     * @covers ::offsetGet
     * @covers ::offsetSet
     * @covers ::offsetUnset
     */
    public function it_implements_the_array_access_interface()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));

        // offsetExists
        $this->assertTrue($collection->offsetExists(0));
        $this->assertFalse($collection->offsetExists(2));

        // offsetGet
        $this->assertInstanceOf(Resource::class, $collection->offsetGet(0));
        $this->assertNull($collection->offsetGet(2));

        // offsetSet
        $collection->offsetSet(2, new Resource());
        $collection->offsetSet(null, new Resource());
        $this->assertInstanceOf(Resource::class, $collection->offsetGet(2));
        $this->assertInstanceOf(Resource::class, $collection->offsetGet(3));

        // offsetUnset
        $collection->offsetUnset(2);
        $this->assertNull($collection->offsetGet(2));
    }

    /**
     * @test
     * @covers ::isEmpty
     * @covers ::isNotEmpty
     */
    public function it_knows_when_it_is_empty()
    {
        $collectionA = ResourceCollection::fromResponse(Response::fake('account_transactions'));
        $collectionB = new ResourceCollection();

        $this->assertFalse($collectionA->isEmpty());
        $this->assertTrue($collectionB->isEmpty());
        $this->assertTrue($collectionA->isNotEmpty());
        $this->assertFalse($collectionB->isNotEmpty());
    }

    /**
     * @test
     * @covers ::toArray
     */
    public function it_can_be_converted_to_an_array()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));
        $arr = $collection->toArray();

        $this->assertTrue(is_array($arr));
        $this->assertCount(2, $arr);
    }

    /**
     * @test
     * @covers ::getLinks
     */
    public function it_returns_the_links()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));
        $links = $collection->getLinks();
        $expected = [
            'self' => 'https://horizon-testnet.stellar.org/accounts/GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW/transactions?cursor=&limit=10&order=asc',
            'next' => 'https://horizon-testnet.stellar.org/accounts/GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW/transactions?cursor=1116579827830784&limit=10&order=asc',
            'prev' => 'https://horizon-testnet.stellar.org/accounts/GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW/transactions?cursor=1115351467184128&limit=10&order=desc'
        ];

        $this->assertEquals($expected, $links);
    }

    /**
     * @test
     * @covers ::getLink
     */
    public function it_returns_a_single_link()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));
        $this->assertEquals(
            'https://horizon-testnet.stellar.org/accounts/GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW/transactions?cursor=&limit=10&order=asc',
            $collection->getLink('self')
        );
    }

    /**
     * @test
     * @covers ::getSelfLink
     */
    public function it_returns_the_self_link()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));
        $this->assertEquals(
            'https://horizon-testnet.stellar.org/accounts/GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW/transactions?cursor=&limit=10&order=asc',
            $collection->getSelfLink()
        );
    }

    /**
     * @test
     * @covers ::getNextLink
     * @covers ::getNextPagingToken
     */
    public function it_returns_the_link_to_the_next_page_of_transactions()
    {
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));
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
        $collection = ResourceCollection::fromResponse(Response::fake('account_transactions'));
        $this->assertEquals(
            'https://horizon-testnet.stellar.org/accounts/GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW/transactions?cursor=1115351467184128&limit=10&order=desc',
            $collection->getPreviousLink()
        );
        $this->assertEquals('1115351467184128', $collection->getPreviousPagingToken());
    }
}
