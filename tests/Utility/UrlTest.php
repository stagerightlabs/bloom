<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Utility;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Utility\Url;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Utility\Url
 */
class UrlTest extends TestCase
{
    public const EXAMPLE = 'https://developer.mozilla.org/en-US/search?q=URL&r=1';
    public const EXAMPLE_WITH_PORT = 'https://developer.mozilla.org:80/en-US/search?q=URL';

    /**
     * @test
     * @covers ::scheme
     */
    public function it_returns_the_url_scheme()
    {
        $this->assertEquals('https://', Url::scheme(self::EXAMPLE));
        $this->assertEquals('', Url::scheme(''));
    }

    /**
     * @test
     * @covers ::authority
     */
    public function it_returns_the_url_authority()
    {
        $this->assertEquals('developer.mozilla.org', Url::authority(self::EXAMPLE));
        $this->assertEquals('developer.mozilla.org:80', Url::authority(self::EXAMPLE_WITH_PORT));
        $this->assertEquals('', Url::authority(''));
    }

    /**
     * @test
     * @covers ::path
     */
    public function it_returns_the_url_path()
    {
        $this->assertEquals('/en-US/search', Url::path(self::EXAMPLE));
        $this->assertEquals('/', Url::path(Url::base(self::EXAMPLE)));
        $this->assertEquals('/', Url::path(''));
    }

    /**
     * @test
     * @covers ::parameters
     */
    public function it_returns_the_query_string_parameters_as_an_array()
    {
        $expected = [
            'q' => 'URL',
            'r' => '1'
        ];
        $this->assertEquals($expected, Url::parameters(self::EXAMPLE));
        $this->assertEquals([], Url::parameters(''));
    }

    /**
     * @test
     * @covers ::parameter
     */
    public function it_returns_a_single_query_string_parameter()
    {
        $this->assertEquals('URL', Url::parameter(self::EXAMPLE, 'q'));
        $this->assertEquals('1', Url::parameter(self::EXAMPLE, 'r'));
        $this->assertNull(Url::parameter(self::EXAMPLE, 'foo'));
    }

    /**
     * @test
     * @covers ::base
     */
    public function it_returns_the_url_base()
    {
        $this->assertEquals('https://developer.mozilla.org', Url::base(self::EXAMPLE));
        $this->assertEquals('', Url::base(''));
    }

    /**
     * @test
     * @covers ::build
     */
    public function it_builds_url_strings()
    {
        $expected = 'https://developer.mozilla.org/en-US/docs/Learn';
        $this->assertEquals($expected, Url::build(
            Url::base(self::EXAMPLE),
            'en-US/docs/Learn',
        ));

        $expected = 'https://developer.mozilla.org/en-US/docs/Learn?foo=bar';
        $this->assertEquals($expected, Url::build(
            Url::base(self::EXAMPLE),
            'en-US/docs/Learn',
            ['foo' => 'bar']
        ));
    }

    /**
     * @test
     * @covers ::build
     */
    public function it_converts_booleans_to_strings_in_url_query_parameters()
    {
        $params = [
            'foo' => true,
            'bar' => false,
            'baz' => null,
        ];
        $url = Url::build('http://example.com', 'path', $params);

        $this->assertEquals('http://example.com/path?foo=true&bar=false', $url);
    }
}
