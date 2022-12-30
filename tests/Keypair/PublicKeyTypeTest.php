<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Keypair;

use StageRightLabs\Bloom\Keypair\PublicKeyType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Keypair\PublicKeyType
 */
class PublicKeyTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_has_options_defined_by_the_stellar_interface_definition_files()
    {
        $publicKeyType = new PublicKeyType();
        $expected = [
            0 => 'publicKeyTypeEd25519',
        ];

        $this->assertEquals($expected, $publicKeyType->getOptions());
    }

    /**
     * @test
     * @covers ::ed25519
     */
    public function it_can_be_instantiated_as_an_ed25519_key_type()
    {
        $publicKeyType = PublicKeyType::ed25519();
        $this->assertEquals('publicKeyTypeEd25519', strval($publicKeyType));
    }
}
