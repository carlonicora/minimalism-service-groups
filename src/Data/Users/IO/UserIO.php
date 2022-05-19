<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\Users\IO;

use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Interfaces\Sql\Abstracts\AbstractSqlIO;
use CarloNicora\Minimalism\Services\Groups\Data\UserGroups\Databases\UserGroupsTable;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\MySQL\Factories\SqlQueryFactory;

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
        $factory = SqlQueryFactory::create(UserGroupsTable::class)
            ->addParameter(UserGroupsTable::groupId, $groupId);

        return $this->data->read(
            queryFactory: $factory,
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
        $factory = SqlQueryFactory::create(UserGroupsTable::class)
            ->addParameter(UserGroupsTable::userId, $userId)
            ->addParameter(UserGroupsTable::groupId, $groupId);

        $recordset = $this->data->read(
            queryFactory: $factory,
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
        $factory = SqlQueryFactory::create(UserGroupsTable::class)
            ->insert()
            ->addParameter(UserGroupsTable::userId, $userId)
            ->addParameter(UserGroupsTable::groupId, $groupId);

        /** @noinspection UnusedFunctionResultInspection */
        $this->data->create(
            queryFactory: $factory,
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
        public function deleteByUserIdGroupId(
        int $userId,
        int $groupId,
    ): void
    {
        $factory = SqlQueryFactory::create(UserGroupsTable::class)
            ->delete()
            ->addParameter(UserGroupsTable::userId, $userId)
            ->addParameter(UserGroupsTable::groupId, $groupId);

        $this->data->delete(
            queryFactory: $factory,
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
        $factory = SqlQueryFactory::create(UserGroupsTable::class)
            ->selectAll()
            ->addParameter(UserGroupsTable::groupId, $groupId);

        $recordset = $this->data->read(
            queryFactory: $factory,
            cacheBuilder: GroupsCacheFactory::groupUsers($groupId),
        );

        $factory = SqlQueryFactory::create(UserGroupsTable::class)
            ->delete()
            ->addParameter(UserGroupsTable::groupId, $groupId);

        $this->data->delete(
            queryFactory: $factory,
            cacheBuilder: GroupsCacheFactory::groupUsers($groupId)
        );

        foreach ($recordset as $record){
            $this->cache->invalidate(
                GroupsCacheFactory::userGroups($record['userId'])
            );
        }
    }
}