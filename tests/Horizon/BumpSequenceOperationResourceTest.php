<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\BumpSequenceOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\BumpSequenceOperationResource
 */
class BumpSequenceOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getBumpTo
     */
    public function it_returns_the_newly_updated_sequence_number()
    {
        $operation = BumpSequenceOperationResource::wrap(
            Response::fake('bump_sequence_operation')->getBody()
        );

        $this->assertEquals('120192344968520085', $operation->getBumpTo());
    }
}
