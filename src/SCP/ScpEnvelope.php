<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ScpEnvelope implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ScpStatement $statement;
    protected Signature $signature;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->statement)) {
            throw new InvalidArgumentException('The SCP envelope is missing a statement');
        }

        if (!isset($this->signature)) {
            throw new InvalidArgumentException('The SCP envelope is missing a signature');
        }

        $xdr->write($this->statement)->write($this->signature);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $scpEnvelope = new static();
        $scpEnvelope->statement = $xdr->read(ScpStatement::class);
        $scpEnvelope->signature = $xdr->read(Signature::class);

        return $scpEnvelope;
    }

    /**
     * Get the statement.
     *
     * @return ScpStatement
     */
    public function getStatement(): ScpStatement
    {
        return $this->statement;
    }

    /**
     * Accept a statement.
     *
     * @param ScpStatement $statement
     * @return static
     */
    public function withStatement(ScpStatement $statement): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->statement = Copy::deep($statement);

        return $clone;
    }

    /**
     * Get the signature.
     *
     * @return Signature
     */
    public function getSignature(): Signature
    {
        return $this->signature;
    }

    /**
     * Accept a signature.
     *
     * @param Signature $signature
     * @return static
     */
    public function withSignature(Signature $signature): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->signature = Copy::deep($signature);

        return $clone;
    }
}
