<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Cryptography\Checksum;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\Checksum
 */
class ChecksumTest extends TestCase
{
    /**
     * @test
     * @covers ::generate
     * @covers ::crc16
     */
    public function it_generates_a_checksum()
    {
        $bytes = Checksum::generate(hex2bin('abcd'));
        $this->assertEquals('65c9', bin2hex($bytes));
    }

    /**
     * @test
     * @covers ::verify
     */
    public function it_verifies_a_checksum()
    {
        $this->assertTrue(Checksum::verify(hex2bin('65c9'), hex2bin('abcd')));
        $this->assertFalse(Checksum::verify(hex2bin('aaaa'), hex2bin('abcd')));
    }
}
