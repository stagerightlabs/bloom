<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\CreateAccountOperationResource;
use StageRightLabs\Bloom\Horizon\OperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\OperationResource
 */
class OperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation_resource_subclass_from_an_array_payload()
    {
        $operation = OperationResource::operation([]);
        $this->assertInstanceOf(OperationResource::class, $operation);

        $operation = OperationResource::operation(['type_i' => 'unknown']);
        $this->assertInstanceOf(OperationResource::class, $operation);

        $json = Response::fake('create_account_operation')->getJson();
        $operation = OperationResource::operation($json->getAll());
        $this->assertInstanceOf(CreateAccountOperationResource::class, $operation);
    }

    /**
     * @test
     * @covers ::getEffectsLink
     */
    public function it_returns_the_effects_link()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(
            'https://horizon.stellar.org/operations/120192344791343105/effects',
            $operation->getEffectsLink()
        );
    }

    /**
     * @test
     * @covers ::getPrecedesLink
     */
    public function it_returns_the_precedes_link()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(
            'https://horizon.stellar.org/effects?order=asc&cursor=120192344791343105',
            $operation->getPrecedesLink()
        );
    }

    /**
     * @test
     * @covers ::getSelfLink
     */
    public function it_returns_the_self_link()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(
            'https://horizon.stellar.org/operations/120192344791343105',
            $operation->getSelfLink()
        );
    }

    /**
     * @test
     * @covers ::getSucceedsLink
     */
    public function it_returns_the_succeeds_link()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(
            'https://horizon.stellar.org/effects?order=desc&cursor=120192344791343105',
            $operation->getSucceedsLink()
        );
    }

    /**
     * @test
     * @covers ::getTransactionLink
     */
    public function it_returns_the_transaction_link()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(
            'https://horizon.stellar.org/transactions/ef0fe04ac3c7de7228ca2598886059868ad05c224a041e8b2d9ee2a8a9dd6894',
            $operation->getTransactionLink()
        );
    }

    /**
     * @test
     * @covers ::getId
     */
    public function it_returns_the_operation_id()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals('120192344791343105', $operation->getId());
    }

    /**
     * @test
     * @covers ::getPagingToken
     */
    public function it_returns_the_paging_token()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals('120192344791343105', $operation->getPagingToken());
    }

    /**
     * @test
     * @covers ::getTypeNumber
     */
    public function it_returns_the_type_number()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(0, $operation->getTypeNumber());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_type()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals('create_account', $operation->getType());
    }

    /**
     * @test
     * @covers ::getTransactionHash
     */
    public function it_returns_the_transaction_hash()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals('ef0fe04ac3c7de7228ca2598886059868ad05c224a041e8b2d9ee2a8a9dd6894', $operation->getTransactionHash());
    }

    /**
     * @test
     * @covers ::transactionWasSuccessful
     */
    public function it_gets_the_transaction_completion_status()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertTrue($operation->transactionWasSuccessful());
    }

    /**
     * @test
     * @covers ::getSourceAddress
     */
    public function it_returns_the_source_account_address()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(
            'GBVFTZL5HIPT4PFQVTZVIWR77V7LWYCXU4CLYWWHHOEXB64XPG5LDMTU',
            $operation->getSourceAddress()
        );
    }

    /**
     * @test
     * @covers ::getSponsorAddress
     */
    public function it_returns_the_sponsor()
    {
        $operation = OperationResource::wrap(
            Response::fake('create_claimable_balance_operation')->getBody()
        );

        $this->assertEquals(
            'GAYOLLLUIZE4DZMBB2ZBKGBUBZLIOYU6XFLW37GBP2VZD3ABNXCW4BVA',
            $operation->getSponsorAddress()
        );
    }

    /**
     * @test
     * @covers ::getCreatedAt
     */
    public function it_returns_the_operation_creation_date()
    {
        $operationA = OperationResource::wrap(
            Response::fake('create_account_operation')->getBody()
        );

        $this->assertEquals(new \DateTime('2020-01-29T19:43:59Z'), $operationA->getCreatedAt());
        $this->assertNull((new OperationResource())->getCreatedAt());
    }
}
