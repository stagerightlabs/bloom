<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\AccountMergeOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\AccountMergeOperationResource
 */
class AccountMergeOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAccountAddress
     */
    public function it_returns_the_account_address()
    {
        $operation = AccountMergeOperationResource::wrap(
            Response::fake('account_merge_operation')->getBody()
        );

        $this->assertEquals(
            'GCVLWV5B3L3YE6DSCCMHLCK7QIB365NYOLQLW3ZKHI5XINNMRLJ6YHVX',
            $operation->getAccountAddress()
        );
    }

    /**
     * @test
     * @covers ::getIntoAddress
     */
    public function it_returns_the_destination_address()
    {
        $operation = AccountMergeOperationResource::wrap(
            Response::fake('account_merge_operation')->getBody()
        );

        $this->assertEquals(
            'GATL3ETTZ3XDGFXX2ELPIKCZL7S5D2HY3VK4T7LRPD6DW5JOLAEZSZBA',
            $operation->getIntoAddress()
        );
    }
}
