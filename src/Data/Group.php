<?php

namespace CarloNicora\Minimalism\Services\Groups\Data;

use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Interfaces\Data\Abstracts\AbstractDataObject;
use Exception;

class Group extends AbstractDataObject
{
    /** @var int  */
    private int $id;

    /** @var string  */
    private string $name;

    /** @var bool  */
    private bool $canCreateGroups=false;

    /**
     * @param array $data
     */
    public function import(
        array $data,
    ): void
    {
        $this->id = $data['groupId'];
        $this->name = $data['name'];
        $this->canCreateGroups = $data['canCreateGroups'] ?? false;
    }

    /**
     * @return array
     */
    public function export(
    ): array
    {
        $response = parent::export();

        $response['groupId'] = $this->id ?? null;
        $response['name'] = $this->name;
        $response['canCreateGroups'] = $this->canCreateGroups;

        return $response;
    }

    /**
     * @return int
     */
    public function getId(
    ): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function canCreateGroups(
    ): bool
    {
        return $this->canCreateGroups;
    }

    /**
     * @param ResourceObject $object
     * @throws Exception
     */
    public function ingestResource(
        ResourceObject $object,
    ): void
    {
        $this->name = $object->attributes->get('name');
        if ($object->attributes->has('canCreateGroups')) {
            $this->canCreateGroups = $object->attributes->get('canCreateGroups');
        }
    }
}