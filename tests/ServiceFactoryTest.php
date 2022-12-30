<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Keypair\KeypairService;
use StageRightLabs\Bloom\ServiceFactory;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ServiceFactory
 */
class ServiceFactoryTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated()
    {
        $factory = new ServiceFactory(new Bloom());
        $this->assertInstanceOf(ServiceFactory::class, $factory);
    }

    /**
     * @test
     * @covers ::__get
     */
    public function it_can_call_registered_service_classes()
    {
        $factory = new ServiceFactory(new Bloom());
        $keypairService = $factory->keypair;

        $this->assertInstanceOf(KeypairService::class, $keypairService);
    }

    /**
     * @test
     * @covers ::__get
     */
    public function it_caches_service_classes()
    {
        $factory = new ServiceFactory(new Bloom());
        $factory->keypair;
        $keypairService = $factory->keypair;

        $this->assertInstanceOf(KeypairService::class, $keypairService);
    }

    /**
     * @test
     * @covers ::__get
     */
    public function calling_an_unknown_service_returns_null()
    {
        $factory = new ServiceFactory(new Bloom());
        $this->assertNull(@$factory->unknown);
    }

    /**
     * @test
     * @covers ::__get
     */
    public function calling_an_unknown_service_triggers_an_error()
    {
        $this->expectError();
        $factory = new ServiceFactory(new Bloom());
        $this->assertNull($factory->unknown);
    }

    /**
     * @test
     * @covers ::makeService
     */
    public function it_can_make_service_classes()
    {
        $factory = new ServiceFactory(new Bloom());
        $service = $factory->makeService('keypair');

        $this->assertInstanceOf(KeypairService::class, $service);
    }

    /**
     * @test
     * @covers ::makeService
     */
    public function attempting_to_make_an_unknown_service_returns_null()
    {
        $factory = new ServiceFactory(new Bloom());
        $service = $factory->makeService('unknown');

        $this->assertNull($service);
    }

    /**
     * @test
     * @covers ::getRegistry
     */
    public function it_returns_the_list_of_registered_service_classes()
    {
        $registry = ServiceFactory::getRegistry();

        $this->assertGreaterThan(1, count($registry));
        $this->assertArrayHasKey('account', $registry);
        $this->assertEquals(\StageRightLabs\Bloom\Account\AccountService::class, $registry['account']);
    }
}
