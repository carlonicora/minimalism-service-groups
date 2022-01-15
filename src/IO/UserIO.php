<?php
namespace CarloNicora\Minimalism\Services\Groups\IO;

use CarloNicora\Minimalism\Services\DataMapper\Abstracts\AbstractLoader;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables\UserGroupsTable;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;

class UserIO extends AbstractLoader
{
    /**
     * @param int $groupId
     * @return array
     */
    public function readByGroupId(
        int $groupId,
    ): array
    {
        /** @see UserGroupsTable::readGroupUsers() */
        return $this->data->read(
            tableInterfaceClassName: UserGroupsTable::class,
            functionName: 'readGroupUsers',
            parameters: [$groupId],
            cacheBuilder: GroupsCacheFactory::groupUsers($groupId),
        );
    }

    /**
     * @param int $userId
     * @param int $groupId
     * @return bool
     */
    public function doesUserBelongsToGroup(
        int $userId,
        int $groupId,
    ): bool
    {
        /** @see UserGroupsTable::readByUserIdGroupId() */
        $recordset = $this->data->read(
            tableInterfaceClassName: UserGroupsTable::class,
            functionName: 'readByUserIdGroupId',
            parameters: [$userId, $groupId],
        );

        return $recordset !== [];
    }

    /**
     * @param int $userId
     * @param int $groupId
     * @return void
     */
    public function insert(
        int $userId,
        int $groupId,
    ): void
    {
        /** @noinspection UnusedFunctionResultInspection */
        $this->data->insert(
            tableInterfaceClassName: UserGroupsTable::class,
            records: [['userId' => $userId, 'groupId' => $groupId]],
        );

        $this->cache->invalidate(
            GroupsCacheFactory::groupUsers($groupId)
        );

        $this->cache->invalidate(
            GroupsCacheFactory::userGroups($userId)
        );
    }

    /**
     * @param int $userId
     * @param int $groupId
     * @return void
     */
    public function delete(
        int $userId,
        int $groupId,
    ): void
    {
        $this->data->delete(
            tableInterfaceClassName: UserGroupsTable::class,
            records: [['userId' => $userId, 'groupId' => $groupId]],
        );

        $this->cache->invalidate(
            GroupsCacheFactory::groupUsers($groupId)
        );

        $this->cache->invalidate(
            GroupsCacheFactory::userGroups($userId)
        );
    }

    /**
     * @param int $groupId
     * @return void
     */
    public function deleteByGroupId(
        int $groupId,
    ): void
    {
        /** @see UserGroupsTable::readGroupUsers() */
        $recordset = $this->data->read(
            tableInterfaceClassName: UserGroupsTable::class,
            functionName: 'readGroupUsers',
            parameters: [$groupId],
            cacheBuilder: GroupsCacheFactory::groupUsers($groupId)
        );

        $this->data->delete(
            tableInterfaceClassName: UserGroupsTable::class,
            records: $recordset,
            cacheBuilder: GroupsCacheFactory::groupUsers($groupId)
        );

        foreach ($recordset as $record){
            $this->cache->invalidate(
                GroupsCacheFactory::userGroups($record['userId'])
            );
        }
    }
}