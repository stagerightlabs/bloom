<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom;

abstract class Service
{
    /**
     * The parent Bloom instance that owns this service class object.
     *
     * @var \StageRightLabs\Bloom\Bloom
     */
    protected $bloom;

    /**
     * Instantiate a new instance of this service class.
     *
     * @param Bloom $bloom
     */
    public function __construct(Bloom $bloom)
    {
        $this->bloom = $bloom;
    }
}
