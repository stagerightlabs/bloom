<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\LiquidityPool;

use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\LiquidityPool\LiquidityPoolType
 */
class LiquidityPoolTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => LiquidityPoolType::LIQUIDITY_POOL_CONSTANT_PRODUCT,
        ];
        $liquidityPoolType = new LiquidityPoolType();

        $this->assertEquals($expected, $liquidityPoolType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $liquidityPoolType = LiquidityPoolType::liquidityPoolConstantProduct();
        $this->assertEquals(LiquidityPoolType::LIQUIDITY_POOL_CONSTANT_PRODUCT, $liquidityPoolType->getType());
    }

    /**
     * @test
     * @covers ::liquidityPoolConstantProduct
     */
    public function it_can_be_instantiated_as_a_constant_product_type()
    {
        $liquidityPoolType = LiquidityPoolType::liquidityPoolConstantProduct();

        $this->assertInstanceOf(LiquidityPoolType::class, $liquidityPoolType);
        $this->assertEquals(LiquidityPoolType::LIQUIDITY_POOL_CONSTANT_PRODUCT, $liquidityPoolType->getType());
    }
}
