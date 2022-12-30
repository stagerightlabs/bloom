<?php


declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Horizon\CurlHttp;
use StageRightLabs\Bloom\Horizon\Error;
use StageRightLabs\Bloom\Horizon\FakeHttp;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\HorizonService
 */
class HorizonServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::get
     */
    public function it_makes_get_requests()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(new Response(200, [], 'fake resource'));

        $resource = $bloom->horizon->get('some/url');

        $this->assertEquals($resource->getResponse()->getBody(), 'fake resource');
    }

    /**
     * @test
     * @covers ::post
     */
    public function it_makes_post_requests()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(new Response(200, [], 'fake resource'));

        $resource = $bloom->horizon->post('some/url', [
            'foo' => 'bar',
        ]);

        $this->assertEquals($resource->getResponse()->getBody(), 'fake resource');
    }

    /**
     * @test
     * @covers ::getClient
     * @covers ::createCurlHttpClient
     */
    public function it_creates_a_live_http_client()
    {
        $bloom = new Bloom();
        $client = $bloom->horizon->getClient();
        $this->assertInstanceOf(CurlHttp::class, $client);
    }

    /**
     * @test
     * @covers ::getClient
     * @covers ::createFakeHttpClient
     */
    public function it_creates_a_fake_http_client()
    {
        $bloom = Bloom::fake();
        $client = $bloom->horizon->getClient();
        $this->assertInstanceOf(FakeHttp::class, $client);
    }

    /**
     * @test
     * @covers ::withResponse
     * @covers ::hasMockedResponses
     */
    public function it_accepts_mocked_responses()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(new Response(200, [], 'fake resource'));
        $this->assertTrue($bloom->horizon->hasMockedResponses());
    }

    /**
     * @test
     * @covers ::get
     * @covers ::post
     * @covers ::responseIsAnError
     */
    public function it_returns_errors()
    {
        $bloom = Bloom::fake();

        $bloom->horizon->withResponse(new Response(400, [], 'fake error'));
        $error = $bloom->horizon->get('some/url');
        $this->assertInstanceOf(Error::class, $error);

        $bloom->horizon->withResponse(new Response(400, [], 'fake error'));
        $error = $bloom->horizon->post('some/url', [
            'foo' => 'bar',
        ]);
        $this->assertInstanceOf(Error::class, $error);
    }
}
