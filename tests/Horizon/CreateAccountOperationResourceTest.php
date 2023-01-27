<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\CreateAccountOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\CreateAccountOperationResource
 */
class CreateAccountOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getStartingBalance
     */
    public function it_returns_the_source_account_address()
    {
        $operation = CreateAccountOperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals('2.0000000', $operation->getStartingBalance()->toNativeString());
        $this->assertNull((new CreateAccountOperationResource())->getStartingBalance());
    }

    /**
     * @test
     * @covers ::getFunderAddress
     */
    public function it_returns_the_funder_address()
    {
        $operation = CreateAccountOperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(
            'GBVFTZL5HIPT4PFQVTZVIWR77V7LWYCXU4CLYWWHHOEXB64XPG5LDMTU',
            $operation->getFunderAddress()
        );
    }

    /**
     * @test
     * @covers ::getAccountAddress
     */
    public function it_returns_the_account_address()
    {
        $operation = CreateAccountOperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(
            'GAYOLLLUIZE4DZMBB2ZBKGBUBZLIOYU6XFLW37GBP2VZD3ABNXCW4BVA',
            $operation->getAccountAddress()
        );
    }
}
