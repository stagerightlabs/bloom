<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\Resource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Utility\Json;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\Resource
 */
class ResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function it_will_be_constructed_with_an_empty_payload()
    {
        $this->assertEquals([], (new Resource())->toArray());
    }

    /**
     * @test
     * @covers ::wrap
     */
    public function it_can_wrap_an_array()
    {
        $resource = Resource::wrap(['foo' => 'bar']);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals(['foo' => 'bar'], $resource->toJson()->getAll());
    }

    /**
     * @test
     * @covers ::wrap
     */
    public function it_can_wrap_a_json_string()
    {
        $resource = Resource::wrap('{"foo": "bar"}');

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals(['foo' => 'bar'], $resource->toJson()->getAll());
    }

    /**
     * @test
     * @covers ::wrap
     */
    public function it_can_wrap_a_json_object()
    {
        $json = Json::of('{"foo": "bar"}');
        $resource = Resource::wrap($json);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals(['foo' => 'bar'], $resource->toJson()->getAll());
    }

    /**
     * @test
     * @covers ::fromResponse
     * @covers ::getResponse
     */
    public function it_can_be_created_from_a_response()
    {
        $resource = Resource::fromResponse(Response::fake('example'));

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Response::class, $resource->getResponse());
    }

    /**
     * @test
     * @covers ::toArray
     */
    public function it_can_be_converted_to_an_array()
    {
        $response = Response::fake('example');
        $resource = Resource::wrap($response->getBody());

        $this->assertEquals(['[foo]' => '[bar]'], $resource->toArray());
    }

    /**
     * @test
     * @covers ::toJson
     */
    public function it_can_be_converted_to_a_json_wrapper_object()
    {
        $response = Response::fake('example');
        $resource = Resource::wrap($response->getBody());

        $this->assertInstanceOf(Json::class, $resource->toJson());
    }

    /**
     * @test
     * @covers ::requestFailed
     */
    public function it_knows_that_it_represents_a_successful_request()
    {
        $resource = Resource::wrap((Response::fake('example'))->getBody());
        $this->assertFalse($resource->requestFailed());
    }

    /**
     * @test
     * @covers ::getLinks
     */
    public function it_returns_the_links_array()
    {
        $resource = Resource::wrap(Response::fake('friendbot_transaction')->getBody());
        $links = $resource->getLinks();
        $expected = [
            'self'        => 'https://horizon-testnet.stellar.org/transactions/[hash]',
            'account'     => 'https://horizon-testnet.stellar.org/accounts/[address]',
            'ledger'      => 'https://horizon-testnet.stellar.org/ledgers/994571',
            'operations'  => 'https://horizon-testnet.stellar.org/transactions/[hash]/operations{?cursor,limit,order}',
            'effects'     => 'https://horizon-testnet.stellar.org/transactions/[hash]/effects{?cursor,limit,order}',
            'precedes'    => 'https://horizon-testnet.stellar.org/transactions?order=asc&cursor=4271649918558208',
            'succeeds'    => 'https://horizon-testnet.stellar.org/transactions?order=desc&cursor=4271649918558208',
            'transaction' => 'https://horizon-testnet.stellar.org/transactions/[hash]',
        ];

        $this->assertEquals($expected, $links);
    }

    /**
     * @test
     * @covers ::getLink
     */
    public function it_returns_a_single_link_from_the_links_array()
    {
        $resource = Resource::wrap(Response::fake('friendbot_transaction')->getBody());
        $expected = 'https://horizon-testnet.stellar.org/transactions/[hash]';

        $this->assertEquals($expected, $resource->getLink('self'));
    }
}
