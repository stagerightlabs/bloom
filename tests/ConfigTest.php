<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Config;
use StageRightLabs\Bloom\Horizon\CurlHttp;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Config
 */
class ConfigTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_via_constructor()
    {
        $config = new Config(['fake' => true]);
        $this->assertTrue($config->isFake());
        $this->assertInstanceOf(Config::class, $config);

        $config = new Config(['fake' => false]);
        $this->assertFalse($config->isFake());
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @test
     * @covers ::make
     */
    public function it_can_be_instantiated_statically()
    {
        $config = Config::make(['fake' => true]);
        $this->assertTrue($config->isFake());
        $this->assertInstanceOf(Config::class, $config);

        $config = Config::make(['fake' => false]);
        $this->assertFalse($config->isFake());
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @test
     * @covers ::toArray
     */
    public function it_can_be_converted_to_an_array()
    {
        $config = new Config();

        $this->assertEquals([
            'debug'              => false,
            'fake'               => false,
            'network_passphrase' => Bloom::TEST_NETWORK_PASSPHRASE,
            'network_url'        => Bloom::TEST_NETWORK_URL,
            'allow_friendbot'    => true,
            'friendbot_url'      => 'https://friendbot.stellar.org/',
            'logging_path'       => CurlHttp::DEFAULT_LOGGING_PATH,
        ], $config->toArray());
    }

    /**
     * @test
     * @covers ::__toString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $config = new Config();

        $this->assertEquals(
            json_encode($config->toArray()),
            strval($config)
        );
    }

    /**
     * @test
     * @covers ::toJson
     */
    public function it_can_be_converted_to_a_json_string()
    {
        $config = new Config();

        $this->assertEquals(
            json_encode($config->toArray()),
            $config->toJson()
        );
    }

    /**
     * @test
     * @covers ::isFake
     * @covers ::withFakeEnabled
     * @covers ::withFakeDisabled
     */
    public function it_can_control_the_fake_mode_flag()
    {
        $configA = new Config();
        $configB = $configA->withFakeEnabled();
        $configC = $configB->withFakeDisabled();

        $this->assertFalse($configA->isFake());
        $this->assertTrue($configB->isFake());
        $this->assertFalse($configC->isFake());
    }

    /**
     * @test
     * @covers ::debugIsEnabled
     * @covers ::withDebugEnabled
     * @covers ::withDebugDisabled
     */
    public function it_can_control_debug_mode()
    {
        $configA = new Config();
        $configB = $configA->withDebugEnabled();
        $configC = $configB->withDebugDisabled();

        $this->assertFalse($configA->debugIsEnabled());
        $this->assertTrue($configB->debugIsEnabled());
        $this->assertFalse($configC->debugIsEnabled());
    }

    /**
     * @test
     * @covers ::getNetworkPassphrase
     * @covers ::withNetworkPassphrase
     */
    public function it_can_control_the_network_passphrase_value()
    {
        $configA = new Config();
        $configB = $configA->withNetworkPassphrase('Some Passphrase');

        $this->assertEquals(Bloom::TEST_NETWORK_PASSPHRASE, $configA->getNetworkPassphrase());
        $this->assertEquals('Some Passphrase', $configB->getNetworkPassphrase());
    }

    /**
     * @test
     * @covers ::getNetworkUrl
     * @covers ::withNetworkUrl
     */
    public function it_can_control_the_network_url_value()
    {
        $configA = new Config();
        $configB = $configA->withNetworkUrl('https://www.example.com');

        $this->assertEquals(Bloom::TEST_NETWORK_URL, $configA->getNetworkUrl());
        $this->assertEquals('https://www.example.com', $configB->getNetworkUrl());
    }

    /**
     * @test
     * @covers ::friendbotIsAllowed
     * @covers ::withFriendbotEnabled
     * @covers ::withFriendbotDisabled
     */
    public function it_can_control_access_to_friendbot()
    {
        $configA = new Config();
        $configB = $configA->withFriendbotDisabled();
        $configC = (new Config())->withFriendbotEnabled();

        $this->assertTrue($configA->friendbotIsAllowed());
        $this->assertFalse($configB->friendbotIsAllowed());
        $this->assertTrue($configC->friendbotIsAllowed());
    }

    /**
     * @test
     * @covers ::getFriendbotUrl
     * @covers ::withFriendbotUrl
     */
    public function it_can_control_the_friendbot_url_value()
    {
        $configA = new Config();
        $configB = $configA->withFriendbotUrl('https://www.example.com');

        $this->assertEquals(Bloom::FRIENDBOT_URL, $configA->getFriendbotUrl());
        $this->assertEquals('https://www.example.com/', $configB->getFriendbotUrl());
        $this->assertEquals('https://www.example.com/foo/bar?baz=bat', $configB->getFriendbotUrl('foo/bar', ['baz' => 'bat']));
    }

    /**
     * @test
     * @covers ::getLoggingPath
     * @covers ::withLoggingPath
     */
    public function it_can_control_the_logging_path_for_debug_mode()
    {
        $configA = new Config();
        $configB = $configA->withLoggingPath('../foo/bar');

        $this->assertEquals(CurlHttp::DEFAULT_LOGGING_PATH, $configA->getLoggingPath());
        $this->assertEquals('../foo/bar', $configB->getLoggingPath());
    }
}
