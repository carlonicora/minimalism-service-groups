<?php

namespace CarloNicora\Minimalism\Services\Groups\Data;

use CarloNicora\Minimalism\Factories\ObjectFactory;
use CarloNicora\Minimalism\Services\DataMapper\Abstracts\AbstractDataObject;
use CarloNicora\Minimalism\Services\Groups\Enums\DefaultGroups;

class Group extends AbstractDataObject
{
    /**
     * @param ObjectFactory $objectFactory
     * @param array|null $data
     * @param int|null $groupId
     */
    public function __construct(
        ObjectFactory $objectFactory,
        ?array $data = null,
        protected ?int $groupId=null,
    )
    {
        if ($data !== null) {
            parent::__construct(
                objectFactory: $objectFactory,
                data: $data,
            );
        }
    }

    /**
     * @param array $data
     */
    public function import(
        array $data,
    ): void
    {
        $this->groupId = $data['groupId'];
    }

    /**
     * @return array
     */
    public function export(
    ): array
    {
        $response = parent::export();

        $response['groupId'] = $this->groupId;

        return $response;
    }

    /**
     * @return int
     */
    public function getGroupId(
    ): int
    {
        return $this->groupId;
    }

    /**
     * @return bool
     */
    public function isAdminGroup(
    ): bool
    {
        return $this->groupId === DefaultGroups::Admin->value;
    }
}