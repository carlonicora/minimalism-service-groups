<?php
namespace CarloNicora\Minimalism\Services\Groups\IO;

use CarloNicora\Minimalism\Services\DataMapper\Abstracts\AbstractLoader;
use CarloNicora\Minimalism\Services\Groups\Data\Group;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables\GroupsTable;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables\UserGroupsTable;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use Exception;

class GroupIO extends AbstractLoader
{
    /**
     * @param int $groupId
     * @return Group
     * @throws Exception
     */
    public function readByGroupId(
        int $groupId,
    ): Group
    {
        /** @see GroupsTable::readById() */
        return $this->returnSingleObject(
            recordset: $this->data->read(
                tableInterfaceClassName: GroupsTable::class,
                functionName: 'readById',
                parameters: [$groupId],
                cacheBuilder: GroupsCacheFactory::group($groupId),
            ),
            objectType: Group::class,
        );
    }

    /**
     * @param string $name
     * @return Group
     * @throws Exception
     */
    public function readByGroupName(
        string $name,
    ): Group
    {
        /** @see GroupsTable::readByGroupName() */
        return $this->returnSingleObject(
            recordset: $this->data->read(
                tableInterfaceClassName: GroupsTable::class,
                functionName: 'readByGroupName',
                parameters: [$name],
            ),
            objectType: Group::class,
        );
    }

    /**
     * @param int $userId
     * @return Group[]
     */
    public function readByUserId(
        int $userId,
    ): array
    {
        /** @see UserGroupsTable::readUserGroups() */
        return $this->returnObjectArray(
            recordset: $this->data->read(
                tableInterfaceClassName: UserGroupsTable::class,
                functionName: 'readUserGroups',
                parameters: [$userId],
                cacheBuilder:GroupsCacheFactory::userGroups($userId),
            ),
            objectType: Group::class,
        );
    }

    /**
     * @param int $groupId
     * @return void
     */
    public function delete(
        int $groupId,
    ): void
    {
        $this->data->delete(
            tableInterfaceClassName: GroupsTable::class,
            records: [['groupId' => $groupId]],
            cacheBuilder: GroupsCacheFactory::group($groupId)
        );
    }
}