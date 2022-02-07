<?php
namespace CarloNicora\Minimalism\Services\Groups\Database\Groups\IO;

use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Interfaces\Sql\Abstracts\AbstractSqlIO;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Caches\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables\UserGroupsTable;
use CarloNicora\Minimalism\Services\MySQL\Factories\SqlFactory;

class UserIO extends AbstractSqlIO
{
    /**
     * @param int $groupId
     * @return array
     * @throws MinimalismException
     */
    public function readByGroupId(
        int $groupId,
    ): array
    {
        $factory = SqlFactory::create(UserGroupsTable::class)
            ->addParameter(UserGroupsTable::groupId, $groupId);

        return $this->data->read(
            factory: $factory,
            cacheBuilder: GroupsCacheFactory::groupUsers($groupId),
        );
    }

    /**
     * @param int $userId
     * @param int $groupId
     * @return bool
     * @throws MinimalismException
     */
    public function doesUserBelongsToGroup(
        int $userId,
        int $groupId,
    ): bool
    {
        $factory = SqlFactory::create(UserGroupsTable::class)
            ->addParameter(UserGroupsTable::userId, $userId)
            ->addParameter(UserGroupsTable::groupId, $groupId);

        $recordset = $this->data->read(
            factory: $factory,
        );

        return $recordset !== [];
    }

    /**
     * @param int $userId
     * @param int $groupId
     * @return void
     * @throws MinimalismException
     */
    public function insert(
        int $userId,
        int $groupId,
    ): void
    {
        $factory = SqlFactory::create(UserGroupsTable::class)
            ->insert()
            ->addParameter(UserGroupsTable::userId, $userId)
            ->addParameter(UserGroupsTable::groupId, $groupId);

        /** @noinspection UnusedFunctionResultInspection */
        $this->data->create(
            factory: $factory,
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
     * @throws MinimalismException
     */
    public function delete(
        int $userId,
        int $groupId,
    ): void
    {
        $factory = SqlFactory::create(UserGroupsTable::class)
            ->delete()
            ->addParameter(UserGroupsTable::userId, $userId)
            ->addParameter(UserGroupsTable::groupId, $groupId);

        $this->data->delete(
            factory: $factory,
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
     * @throws MinimalismException
     */
    public function deleteByGroupId(
        int $groupId,
    ): void
    {
        $factory = SqlFactory::create(UserGroupsTable::class)
            ->selectAll()
            ->addParameter(UserGroupsTable::groupId, $groupId);

        $recordset = $this->data->read(
            factory: $factory,
            cacheBuilder: GroupsCacheFactory::groupUsers($groupId),
        );

        $factory = SqlFactory::create(UserGroupsTable::class)
            ->delete()
            ->addParameter(UserGroupsTable::groupId, $groupId);

        $this->data->delete(
            factory: $factory,
            cacheBuilder: GroupsCacheFactory::groupUsers($groupId)
        );

        foreach ($recordset as $record){
            $this->cache->invalidate(
                GroupsCacheFactory::userGroups($record['userId'])
            );
        }
    }
}