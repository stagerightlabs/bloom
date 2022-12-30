<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Utility;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Utility\Base32;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Utility\Base32
 */
class Base32Test extends TestCase
{
    /**
     * @test
     * @covers ::encode
     */
    public function it_encodes_into_base32_strings()
    {
        $this->assertEquals('', Base32::encode(''));
        $this->assertEquals('MY', Base32::encode('f'));
        $this->assertEquals('MY======', Base32::encode('f', true));
        $this->assertEquals('MZXQ', Base32::encode('fo'));
        $this->assertEquals('MZXQ====', Base32::encode('fo', true));
        $this->assertEquals('MZXW6', Base32::encode('foo'));
        $this->assertEquals('MZXW6===', Base32::encode('foo', true));
        $this->assertEquals('MZXW6YQ', Base32::encode('foob'));
        $this->assertEquals('MZXW6YQ=', Base32::encode('foob', true));
        $this->assertEquals('MZXW6YTB', Base32::encode('fooba'));
        $this->assertEquals('MZXW6YTB', Base32::encode('fooba', true));
        $this->assertEquals('MZXW6YTBOI', Base32::encode('foobar'));
        $this->assertEquals('MZXW6YTBOI======', Base32::encode('foobar', true));
    }

    /**
     * @test
     * @covers ::decode
     */
    public function it_decodes_into_base32_strings()
    {
        $this->assertEquals('', Base32::decode(''));
        $this->assertEquals('f', Base32::decode('MY'));
        $this->assertEquals('f', Base32::decode('MY======'));
        $this->assertEquals('fo', Base32::decode('MZXQ'));
        $this->assertEquals('fo', Base32::decode('MZXQ===='));
        $this->assertEquals('foo', Base32::decode('MZXW6'));
        $this->assertEquals('foo', Base32::decode('MZXW6==='));
        $this->assertEquals('foob', Base32::decode('MZXW6YQ'));
        $this->assertEquals('foob', Base32::decode('MZXW6YQ='));
        $this->assertEquals('fooba', Base32::decode('MZXW6YTB'));
        $this->assertEquals('fooba', Base32::decode('MZXW6YTB'));
        $this->assertEquals('foobar', Base32::decode('MZXW6YTBOI'));
        $this->assertEquals('foobar', Base32::decode('MZXW6YTBOI======'));
    }

    /**
     * @test
     * @covers ::decode
     */
    public function it_ignores_invalid_base32_alphabet_characters()
    {
        $this->assertEquals('', Base32::decode('?<>!1111'));
        $this->assertEquals('foobar', Base32::decode('M?ZXW<6>YTB!OI111'));
    }
}
