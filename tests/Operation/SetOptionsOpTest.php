<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\OptionalAccountId;
use StageRightLabs\Bloom\Account\OptionalSigner;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\SetOptionsOp;
use StageRightLabs\Bloom\Primitives\OptionalString32;
use StageRightLabs\Bloom\Primitives\OptionalUInt32;
use StageRightLabs\Bloom\Primitives\String32;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\SetOptionsOp
 */
class SetOptionsOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = SetOptionsOp::operation(
            inflationDestination: Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')->getAccountId(),
            clearFlags: 1,
            setFlags: 2,
            masterWeight: 3,
            lowThreshold: 4,
            mediumThreshold: 5,
            highThreshold: 6,
            homeDomain: 'example.com',
            signer: Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')->getWeightedSigner(255)
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(SetOptionsOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $this->assertTrue((new SetOptionsOp())->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $operationA = new SetOptionsOp();
        $operationB = $operationA->withMasterWeight(1);

        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, $operationA->getThreshold());
        $this->assertEquals(Thresholds::CATEGORY_HIGH, $operationB->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $buffer = XDR::fresh()->write(new SetOptionsOp());
        $this->assertEquals('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $setOptions = XDR::fromBase64('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA')
            ->read(SetOptionsOp::class);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptions);
        $this->assertNull($setOptions->getInflationDestination());
        $this->assertNull($setOptions->getClearFlags());
        $this->assertNull($setOptions->getSetFlags());
        $this->assertNull($setOptions->getMasterWeight());
        $this->assertNull($setOptions->getLowThreshold());
        $this->assertNull($setOptions->getMediumThreshold());
        $this->assertNull($setOptions->getHighThreshold());
        $this->assertNull($setOptions->getHomeDomain());
        $this->assertNull($setOptions->getSigner());
    }

    /**
     * @test
     * @covers ::withInflationDestination
     * @covers ::getInflationDestination
     */
    public function it_accepts_an_account_id_as_an_inflation_destination()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $setOptionsOp = (new SetOptionsOp())
            ->withInflationDestination($accountId);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $setOptionsOp->getInflationDestination()->getAddress());
    }

    /**
     * @test
     * @covers ::withInflationDestination
     * @covers ::getInflationDestination
     */
    public function it_accepts_an_optional_account_id_as_an_inflation_destination()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $setOptionsOp = (new SetOptionsOp())
            ->withInflationDestination(OptionalAccountId::some($accountId));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $setOptionsOp->getInflationDestination()->getAddress());
    }

    /**
     * @test
     * @covers ::withInflationDestination
     * @covers ::getInflationDestination
     */
    public function it_accepts_null_as_an_inflation_destination()
    {
        $setOptionsOp = (new SetOptionsOp())->withInflationDestination(null);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertNull($setOptionsOp->getInflationDestination());
    }

    /**
     * @test
     * @covers ::withClearFlags
     * @covers ::getClearFlags
     */
    public function it_accepts_an_uint_32_as_flags_to_be_cleared()
    {
        $setOptionsOp = (new SetOptionsOp())->withClearFlags(UInt32::of(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getClearFlags()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withClearFlags
     * @covers ::getClearFlags
     */
    public function it_accepts_an_optional_uint32_as_flags_to_be_cleared()
    {
        $setOptionsOp = (new SetOptionsOp())->withClearFlags(OptionalUInt32::some(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getClearFlags()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withClearFlags
     * @covers ::getClearFlags
     */
    public function it_accepts_a_native_int_as_flags_to_be_cleared()
    {
        $setOptionsOp = (new SetOptionsOp())->withClearFlags(1);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getClearFlags()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withClearFlags
     * @covers ::getClearFlags
     */
    public function it_accepts_null_as_flags_to_be_cleared()
    {
        $setOptionsOp = (new SetOptionsOp())->withClearFlags(null);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertNull($setOptionsOp->getClearFlags());
    }

    /**
     * @test
     * @covers ::withSetFlags
     * @covers ::getSetFlags
     */
    public function it_accepts_an_uint_32_as_flags_to_be_set()
    {
        $setOptionsOp = (new SetOptionsOp())->withSetFlags(UInt32::of(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getSetFlags()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withSetFlags
     * @covers ::getSetFlags
     */
    public function it_accepts_an_optional_uint_32_as_flags_to_be_set()
    {
        $setOptionsOp = (new SetOptionsOp())->withSetFlags(OptionalUInt32::some(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getSetFlags()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withSetFlags
     * @covers ::getSetFlags
     */
    public function it_accepts_an_native_int_as_flags_to_be_set()
    {
        $setOptionsOp = (new SetOptionsOp())->withSetFlags(1);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getSetFlags()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withSetFlags
     * @covers ::getSetFlags
     */
    public function it_accepts_null_as_flags_to_be_set()
    {
        $setOptionsOp = (new SetOptionsOp())->withSetFlags(null);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertNull($setOptionsOp->getSetFlags());
    }

    /**
     * @test
     * @covers ::withMasterWeight
     * @covers ::getMasterWeight
     */
    public function it_accepts_an_uint_32_as_a_master_weight()
    {
        $setOptionsOp = (new SetOptionsOp())->withMasterWeight(UInt32::of(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getMasterWeight()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMasterWeight
     * @covers ::getMasterWeight
     */
    public function it_accepts_an_optional_uint_32_as_a_master_weight()
    {
        $setOptionsOp = (new SetOptionsOp())->withMasterWeight(OptionalUInt32::some(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getMasterWeight()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMasterWeight
     * @covers ::getMasterWeight
     */
    public function it_accepts_a_native_int_as_a_master_weight()
    {
        $setOptionsOp = (new SetOptionsOp())->withMasterWeight(1);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getMasterWeight()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMasterWeight
     * @covers ::getMasterWeight
     */
    public function it_accepts_null_as_a_master_weight()
    {
        $setOptionsOp = (new SetOptionsOp())->withMasterWeight(null);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertNull($setOptionsOp->getMasterWeight());
    }

    /**
     * @test
     * @covers ::withLowThreshold
     * @covers ::getLowThreshold
     */
    public function it_accepts_an_uint_32_as_a_low_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withLowThreshold(UInt32::of(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getLowThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withLowThreshold
     * @covers ::getLowThreshold
     */
    public function it_accepts_an_optional_uint_32_as_a_low_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withLowThreshold(OptionalUInt32::some(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getLowThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withLowThreshold
     * @covers ::getLowThreshold
     */
    public function it_accepts_a_native_int_as_a_low_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withLowThreshold(1);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getLowThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withLowThreshold
     * @covers ::getLowThreshold
     */
    public function it_accepts_null_as_a_low_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withLowThreshold(null);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertNull($setOptionsOp->getLowThreshold());
    }

    /**
     * @test
     * @covers ::withMediumThreshold
     * @covers ::getMediumThreshold
     */
    public function it_accepts_an_uint_32_as_a_medium_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withMediumThreshold(UInt32::of(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getMediumThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMediumThreshold
     * @covers ::getMediumThreshold
     */
    public function it_accepts_an_optional_uint_32_as_a_medium_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withMediumThreshold(OptionalUInt32::some(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getMediumThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMediumThreshold
     * @covers ::getMediumThreshold
     */
    public function it_accepts_a_native_int_as_a_medium_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withMediumThreshold(1);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getMediumThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMediumThreshold
     * @covers ::getMediumThreshold
     */
    public function it_accepts_null_as_a_medium_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withMediumThreshold(null);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertNull($setOptionsOp->getMediumThreshold());
    }

    /**
     * @test
     * @covers ::withHighThreshold
     * @covers ::getHighThreshold
     */
    public function it_accepts_an_uint_32_as_a_high_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withHighThreshold(UInt32::of(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getHighThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withHighThreshold
     * @covers ::getHighThreshold
     */
    public function it_accepts_an_optional_uint_32_as_a_high_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withHighThreshold(OptionalUInt32::some(1));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getHighThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withHighThreshold
     * @covers ::getHighThreshold
     */
    public function it_accepts_a_native_int_as_a_high_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withHighThreshold(1);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals(1, $setOptionsOp->getHighThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withHighThreshold
     * @covers ::getHighThreshold
     */
    public function it_accepts_null_as_a_high_threshold()
    {
        $setOptionsOp = (new SetOptionsOp())->withHighThreshold(null);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertNull($setOptionsOp->getHighThreshold());
    }

    /**
     * @test
     * @covers ::withHomeDomain
     * @covers ::getHomeDomain
     */
    public function it_accepts_a_string_32_as_a_home_domain()
    {
        $setOptionsOp = (new SetOptionsOp())->withHomeDomain(String32::of('https://example.com'));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals('https://example.com', $setOptionsOp->getHomeDomain()->toNativeString());
    }

    /**
     * @test
     * @covers ::withHomeDomain
     * @covers ::getHomeDomain
     */
    public function it_accepts_an_optional_string_32_as_a_home_domain()
    {
        $setOptionsOp = (new SetOptionsOp())->withHomeDomain(OptionalString32::some('https://example.com'));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals('https://example.com', $setOptionsOp->getHomeDomain()->toNativeString());
    }

    /**
     * @test
     * @covers ::withHomeDomain
     * @covers ::getHomeDomain
     */
    public function it_accepts_a_native_string_as_a_home_domain()
    {
        $setOptionsOp = (new SetOptionsOp())->withHomeDomain('https://example.com');

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals('https://example.com', $setOptionsOp->getHomeDomain()->toNativeString());
    }

    /**
     * @test
     * @covers ::withHomeDomain
     * @covers ::getHomeDomain
     */
    public function it_accepts_null_as_a_home_domain()
    {
        $setOptionsOp = (new SetOptionsOp())->withHomeDomain(null);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertNull($setOptionsOp->getHomeDomain());
    }

    /**
     * @test
     * @covers ::withSigner
     * @covers ::getSigner
     */
    public function it_accepts_a_signer_as_a_signer()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);
        $signer = Signer::of($signerKey, 1);
        $setOptionsOp = (new SetOptionsOp())->withSigner($signer);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $setOptionsOp->getSigner()->getSignerKey()->unwrap()->getAddress());
    }

    /**
     * @test
     * @covers ::withSigner
     * @covers ::getSigner
     */
    public function it_accepts_an_optional_signer_as_a_signer()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);
        $signer = Signer::of($signerKey, 1);
        $setOptionsOp = (new SetOptionsOp())->withSigner(OptionalSigner::some($signer));

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $setOptionsOp->getSigner()->getSignerKey()->unwrap()->getAddress());
    }

    /**
     * @test
     * @covers ::withSigner
     * @covers ::getSigner
     */
    public function it_accepts_null_as_a_signer()
    {
        $setOptionsOp = (new SetOptionsOp())->withSigner(null);

        $this->assertInstanceOf(SetOptionsOp::class, $setOptionsOp);
        $this->assertNull($setOptionsOp->getSigner());
    }
}
