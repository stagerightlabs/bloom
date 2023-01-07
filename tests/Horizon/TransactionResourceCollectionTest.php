<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Horizon\TransactionResource;
use StageRightLabs\Bloom\Horizon\TransactionResourceCollection;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\TransactionResourceCollection
 */
class TransactionResourceCollectionTest extends TestCase
{
    /**
     * @test
     * @covers ::getResourceClass
     */
    public function it_defines_an_underlying_resource_class()
    {
        $collection = TransactionResourceCollection::fromResponse(Response::fake('account_transactions'));

        foreach ($collection as $r) {
            $this->assertInstanceOf(TransactionResource::class, $r);
        }
    }
}
