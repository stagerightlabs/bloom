<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Friendbot;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Config;
use StageRightLabs\Bloom\Exception\BloomException;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Horizon\TransactionResource;
use StageRightLabs\Bloom\Keypair\Keypair;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Friendbot\FriendbotService
 */
class FriendbotServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::fund
     */
    public function it_funds_a_new_account_with_friendbot_using_a_string_address()
    {
        $response = Response::fake('friendbot_transaction', ['address' => 'abdcefg']);
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse($response);

        $friendbotResource = $bloom->friendbot->fund('abdcefg');

        $this->assertInstanceOf(TransactionResource::class, $friendbotResource);
    }

    /**
     * @test
     * @covers ::fund
     */
    public function it_funds_a_new_account_with_friendbot_using_an_addressable()
    {
        $addressable = Keypair::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $response = Response::fake('friendbot_transaction', [
            'address' => 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        ]);
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse($response);

        $friendbotResource = $bloom->friendbot->fund($addressable);

        $this->assertInstanceOf(TransactionResource::class, $friendbotResource);
    }

    /**
     * @test
     * @covers ::fund
     */
    public function disabling_friendbot_will_throw_an_exception()
    {
        $this->expectException(BloomException::class);
        $config = (new Config())->withFakeEnabled()->withFriendbotDisabled();
        $bloom = new Bloom($config);

        $bloom->friendbot->fund('abdcefg');
    }
}
