<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Config;
use StageRightLabs\Bloom\Keypair\KeypairService;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Bloom
 */
class BloomTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_via_constructor()
    {
        $loom = new Bloom();
        $this->assertInstanceOf(Bloom::class, $loom);
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_via_constructor_with_a_config_object()
    {
        $loom = new Bloom(new Config());
        $this->assertInstanceOf(Bloom::class, $loom);
    }

    /**
     * @test
     * @covers ::make
     */
    public function it_can_be_instantiated_statically()
    {
        $loom = Bloom::make();
        $this->assertInstanceOf(Bloom::class, $loom);
    }

    /**
     * @test
     * @covers ::fake
     */
    public function it_can_be_instantiated_in_fake_mode()
    {
        $loom = Bloom::fake(['fake' => false]);

        $this->assertInstanceOf(Bloom::class, $loom);
        $this->assertTrue($loom->isFake());
    }

    /**
     * @test
     * @covers ::fake
     */
    public function it_can_be_instantiated_in_fake_mode_with_a_config_object()
    {
        $config = (new Config())->withFakeDisabled();
        $loom = Bloom::fake($config);

        $this->assertInstanceOf(Bloom::class, $loom);
        $this->assertTrue($loom->isFake());
    }

    /**
     * @test
     * @covers ::isFake
     */
    public function it_knows_when_it_is_in_fake_mode()
    {
        $loom = Bloom::make();
        $this->assertFalse($loom->isFake());

        $loom = Bloom::fake();
        $this->assertTrue($loom->isFake());
    }

    /**
     * @test
     * @covers ::__get
     */
    public function it_can_reference_service_classes()
    {
        $loom = new Bloom();
        $service = $loom->keypair;

        $this->assertInstanceOf(KeypairService::class, $service);
    }
}
