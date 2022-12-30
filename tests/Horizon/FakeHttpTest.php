<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\FakeHttp;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\FakeHttp
 */
class FakeHttpTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_with_an_array_of_responses()
    {
        $response = new Response(200, [], 'hello fake');
        $fake = new FakeHttp([$response]);

        $this->assertInstanceOf(FakeHttp::class, $fake);
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_with_a_single_responses()
    {
        $response = new Response(200, [], 'hello fake');
        $fake = new FakeHttp($response);

        $this->assertInstanceOf(FakeHttp::class, $fake);
    }

    /**
     * @test
     * @covers ::get
     */
    public function it_simulates_get_requests()
    {
        $simulated = new Response(123, [], 'hello fake');
        $fake = new FakeHttp($simulated);
        $response = $fake->get('some/url');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(123, $response->getStatusCode());
    }

    /**
     * @test
     * @covers ::get
     */
    public function it_throws_an_exception_if_the_mocked_responses_queue_is_empty_when_simulating_get_requests()
    {
        $this->expectException(\Exception::class);
        $fake = new FakeHttp();
        $fake->get('some/url');
    }

    /**
     * @test
     * @covers ::post
     */
    public function it_simulates_post_requests()
    {
        $simulated = Response::fake('transaction_submitted', [], 123);
        $fake = new FakeHttp($simulated);
        $response = $fake->post('some/url', [
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(123, $response->getStatusCode());
    }

    /**
     * @test
     * @covers ::post
     */
    public function it_throws_an_exception_if_the_mocked_responses_queue_is_empty_when_simulating_post_requests()
    {
        $this->expectException(\Exception::class);
        $fake = new FakeHttp();
        $fake->post('some/url', [
            'foo' => 'bar'
        ]);
    }
}
