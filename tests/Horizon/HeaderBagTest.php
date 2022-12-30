<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\HeaderBag;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\HeaderBag
 */
class HeaderBagTest extends TestCase
{
    protected const raw = "
HTTP/2 200 OK\r\n
server: nginx\r\n
date: Mon, 16 May 2022 22:49:54 GMT\r\n
content-type: application/json\r\n
vary: Accept-Encoding\r\n
\r\n
\r\n
            ";

    /**
     * @test
     * @covers ::__construct
     * @covers ::make
     * @covers ::parseHeaderString
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $headerBag = HeaderBag::make(self::raw);

        $this->assertInstanceOf(HeaderBag::class, $headerBag);
        $this->assertTrue($headerBag->hasHeader('content-type'));
    }

    /**
     * @test
     * @covers ::hasHeader
     */
    public function it_can_check_for_header_existence()
    {
        $headerBag = HeaderBag::make(self::raw);

        $this->assertInstanceOf(HeaderBag::class, $headerBag);
        $this->assertTrue($headerBag->hasHeader('content-type'));
        $this->assertFalse($headerBag->hasHeader('foo-bar'));
    }

    /**
     * @test
     * @covers ::getHeader
     */
    public function it_returns_a_header_value()
    {
        $headerBag = HeaderBag::make(self::raw);
        $this->assertEquals(['application/json'], $headerBag->getHeader('content-type'));
    }

    /**
     * @test
     * @covers ::getAll
     */
    public function it_returns_all_headers()
    {
        $headerBag = HeaderBag::make(self::raw);
        $expected = [
            'server'       => ['nginx'],
            'date'         => ['Mon, 16 May 2022 22:49:54 GMT'],
            'content-type' => ['application/json'],
            'vary'         => ['Accept-Encoding'],
        ];

        $this->assertEquals($expected, $headerBag->getAll());
    }

    /**
     * @test
     * @covers ::getHeader
     */
    public function a_non_existent_header_value_returns_an_empty_array()
    {
        $headerBag = HeaderBag::make(self::raw);
        $this->assertEquals([], $headerBag->getHeader('foo-bar'));
    }
}
