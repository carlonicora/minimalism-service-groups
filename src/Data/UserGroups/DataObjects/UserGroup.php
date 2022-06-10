<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\UserGroups\DataObjects;

use CarloNicora\Minimalism\Interfaces\Sql\Attributes\DbField;
use CarloNicora\Minimalism\Interfaces\Sql\Attributes\DbTable;
use CarloNicora\Minimalism\Interfaces\Sql\Interfaces\SqlDataObjectInterface;
use CarloNicora\Minimalism\Interfaces\Sql\Traits\SqlDataObjectTrait;
use CarloNicora\Minimalism\Services\Groups\Data\UserGroups\Databases\UserGroupsTable;

#[DbTable(tableClass: UserGroupsTable::class)]
class UserGroup implements SqlDataObjectInterface
{
    use SqlDataObjectTrait;

    /** @var int */
    #[DbField]
    private int $groupId;

    /** @var int */
    #[DbField]
    private int $userId;

    /**
     * @return int
     */
    public function getGroupId(): int
    {
        return $this->groupId;
    }

    /**
     * @param int $groupId
     */
    public function setGroupId(int $groupId): void
    {
        $this->groupId = $groupId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

}