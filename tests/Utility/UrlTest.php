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
    public const EXAMPLE = 'https://developer.mozilla.org/en-US/search?q=URL';
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
     * @covers ::query
     */
    public function it_returns_the_query_string_parameters_as_an_array()
    {
        $this->assertEquals(['q' => 'URL'], Url::query(self::EXAMPLE));
        $this->assertEquals([], Url::query(''));
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
}
