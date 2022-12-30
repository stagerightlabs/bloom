<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\Resource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Horizon\TransactionResource;
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
    public function it_can_be_instantiated_with_an_array_constructor_payload()
    {
        $resource = new Resource(['foo' => 'bar']);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals(['foo' => 'bar'], $resource->toJson()->getAll());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_with_a_string_constructor_payload()
    {
        $resource = new Resource('{"foo": "bar"}');

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals(['foo' => 'bar'], $resource->toJson()->getAll());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_with_a_json_object_constructor_payload()
    {
        $json = Json::of('{"foo": "bar"}');
        $resource = new Resource($json);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals(['foo' => 'bar'], $resource->toJson()->getAll());
    }

    /**
     * @test
     * @covers ::fromArray
     */
    public function it_can_be_instantiated_from_an_array()
    {
        $resource = Resource::fromArray(['foo' => 'bar']);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals(['foo' => 'bar'], $resource->toJson()->getAll());
    }

    /**
     * @test
     * @covers ::fromResponse
     */
    public function it_can_be_instantiated_from_a_response()
    {
        $resource = Resource::fromResponse(Response::fake('example'));

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals(['[foo]' => '[bar]'], $resource->toJson()->getAll());
    }

    /**
     * @test
     * @covers ::getResponse
     */
    public function it_returns_the_original_response_when_present()
    {
        $resource = Resource::fromResponse(Response::fake('example', [], 400));
        $this->assertInstanceOf(Response::class, $resource->getResponse());
    }

    /**
     * @test
     * @covers ::fromResource
     */
    public function a_child_resource_can_be_created_from_a_generic_resource()
    {
        $resource = Resource::fromResponse(Response::fake('example'));
        $child = TransactionResource::fromResource($resource);

        $this->assertInstanceOf(TransactionResource::class, $child);
        $this->assertEquals(['[foo]' => '[bar]'], $child->toJson()->getAll());
    }

    /**
     * @test
     * @covers ::toArray
     */
    public function it_can_be_converted_to_an_array()
    {
        $resource = Resource::fromResponse(Response::fake('example'));
        $this->assertEquals(['[foo]' => '[bar]'], $resource->toArray());
    }

    /**
     * @test
     * @covers ::toJson
     */
    public function it_can_be_converted_to_a_json_wrapper_object()
    {
        $resource = Resource::fromResponse(Response::fake('example'));
        $this->assertInstanceOf(Json::class, $resource->toJson());
    }

    /**
     * @test
     * @covers ::requestFailed
     */
    public function it_knows_that_it_represents_a_successful_request()
    {
        $resource = Resource::fromResponse(Response::fake('example'));
        $this->assertFalse($resource->requestFailed());
    }
}
