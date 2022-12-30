<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Exception;

class InvalidKeyException extends BloomException
{
    /**
     * Properties
     */
    public string $key;

    /**
     * Get the key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Set the key.
     *
     * @param string $key
     *
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }
}
