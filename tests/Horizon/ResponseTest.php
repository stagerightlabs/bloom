<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Utility\Json;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\Response
 */
class ResponseTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated()
    {
        $response = new Response(200, ['X-Foo' => 'bar'], 'hello world');
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @covers ::getStatusCode
     */
    public function it_can_return_the_response_status_code()
    {
        $response = new Response(200, ['X-Foo' => 'bar'], 'hello world');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @covers ::getHeaders
     */
    public function it_can_return_the_response_headers()
    {
        $response = new Response(200, ['X-Foo' => 'bar'], 'hello world');
        $this->assertEquals(['X-Foo' => 'bar'], $response->getHeaders()->getAll());
    }

    /**
     * @test
     * @covers ::getBody
     */
    public function it_can_return_the_response_body()
    {
        $response = new Response(200, ['X-Foo' => 'bar'], 'hello world');
        $this->assertEquals('hello world', $response->getBody());
    }

    /**
     * @test
     * @covers ::getTotalTime
     */
    public function it_returns_the_request_execution_time()
    {
        $response = new Response(200, ['X-Foo' => 'bar'], 'hello world', 500);
        $this->assertEquals(500, $response->getTotalTime());
    }

    /**
     * @test
     * @covers ::fake
     */
    public function it_can_be_generated_from_json_stubs()
    {
        $response = Response::fake('generic_error', [], 400);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @test
     * @covers ::fake
     */
    public function it_rejects_stub_names_that_do_not_exist()
    {
        $this->expectException(\Exception::class);
        Response::fake('nope', [], 400);
    }

    /**
     * @test
     * @covers ::fake
     */
    public function it_swaps_stub_content_when_faking()
    {
        $response = Response::fake('example', ['foo' => 'hello', 'bar' => 'world'], 200);
        $payload = Json::of($response->getBody());

        $this->assertEquals(['hello' => 'world'], $payload->getAll());
    }
}
