<?php

namespace CarloNicora\Minimalism\Services\Groups\Data\Users\DataObjects;

use CarloNicora\Minimalism\Services\ResourceBuilder\Interfaces\ResourceableDataInterface;

class User implements ResourceableDataInterface
{

    /** @var int */
    protected int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(
        int $id
    ): void
    {
        $this->id = $id;
    }

}