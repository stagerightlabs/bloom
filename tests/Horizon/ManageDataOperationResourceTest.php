<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\ManageDataOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\ManageDataOperationResource
 */
class ManageDataOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getDataEntryName
     */
    public function it_returns_the_data_entry_name()
    {
        $operation = ManageDataOperationResource::wrap(
            Response::fake('manage_data_operation')->getBody()
        );

        $this->assertEquals('config.memo_required', $operation->getDataEntryName());
    }

    /**
     * @test
     * @covers ::getDataEntryValue
     */
    public function it_returns_the_data_entry_value()
    {
        $operation = ManageDataOperationResource::wrap(
            Response::fake('manage_data_operation')->getBody()
        );

        $this->assertEquals('MQ==', $operation->getDataEntryValue());
    }
}
