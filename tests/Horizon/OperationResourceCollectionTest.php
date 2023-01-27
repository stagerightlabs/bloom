<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\OperationResource;
use StageRightLabs\Bloom\Horizon\OperationResourceCollection;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\OperationResourceCollection
 */
class OperationResourceCollectionTest extends TestCase
{
    /**
     * @test
     * @covers ::getResourceClass
     */
    public function it_defines_an_underlying_resource_class()
    {
        $collection = OperationResourceCollection::fromResponse(Response::fake('account_operations'));

        foreach ($collection as $r) {
            $this->assertInstanceOf(OperationResource::class, $r);
        }
    }
}
