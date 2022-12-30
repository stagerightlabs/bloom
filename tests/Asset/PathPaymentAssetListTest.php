<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Asset;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\PathPaymentAssetList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\PathPaymentAssetList
 */
class PathPaymentAssetListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(Asset::class, PathPaymentAssetList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(PathPaymentAssetList::MAX_LENGTH, PathPaymentAssetList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $pathPaymentAssetList = PathPaymentAssetList::empty();

        $this->assertInstanceOf(PathPaymentAssetList::class, $pathPaymentAssetList);
        $this->assertEmpty($pathPaymentAssetList);
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_normalized_a_mixed_native_array()
    {
        $arr = [
            Asset::fromNativeString('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'),
            'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'
        ];
        $pathPaymentAssetList = PathPaymentAssetList::normalize($arr);

        $this->assertInstanceOf(PathPaymentAssetList::class, $pathPaymentAssetList);
        $this->assertCount(3, $pathPaymentAssetList);
        foreach ($pathPaymentAssetList as $asset) {
            $this->assertInstanceOf(Asset::class, $asset);
        }
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_normalize_an_instance_of_itself()
    {
        $arr = PathPaymentAssetList::empty();
        $copy = PathPaymentAssetList::normalize($arr);

        $this->assertNotEquals(spl_object_id($arr), spl_object_id($copy));
    }
}
