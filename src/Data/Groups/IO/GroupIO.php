<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\Groups\IO;

use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Interfaces\Sql\Abstracts\AbstractSqlIO;
use CarloNicora\Minimalism\Interfaces\Sql\Factories\SqlQueryFactory;
use CarloNicora\Minimalism\Interfaces\Sql\Factories\SqlJoinFactory;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\Databases\GroupsTable;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\DataObjects\Group;
use CarloNicora\Minimalism\Services\Groups\Data\UserGroups\Databases\UserGroupsTable;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use Exception;

class GroupIO extends AbstractSqlIO
{
    /**
     * @return Group[]
     * @throws Exception
     */
    public function readAll(
    ): array
    {
        $factory = SqlQueryFactory::create(GroupsTable::class);

        return $this->data->read(
            queryFactory: $factory,
            responseType: Group::class,
            requireObjectsList: true,
        );
    }

    /**
     * @param int $groupId
     * @return Group
     * @throws Exception
     */
    public function readByGroupId(
        int $groupId,
    ): Group
    {
        $factory = SqlQueryFactory::create(GroupsTable::class)
            ->addParameter(GroupsTable::groupId, $groupId);

        return $this->data->read(
            queryFactory: $factory,
            cacheBuilder: GroupsCacheFactory::group($groupId),
            responseType: Group::class,
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
        $factory = SqlQueryFactory::create(GroupsTable::class)
            ->addParameter(GroupsTable::name, $name);

        return $this->data->read(
            queryFactory: $factory,
            responseType: Group::class,
        );
    }

    /**
     * @param int $userId
     * @return Group[]
     * @throws MinimalismException
     */
    public function readByUserId(
        int $userId,
    ): array
    {
        $factory = SqlQueryFactory::create(GroupsTable::class)
            ->addJoin(SqlJoinFactory::create(GroupsTable::groupId, UserGroupsTable::groupId))
            ->addParameter(UserGroupsTable::userId, $userId);

        return $this->data->read(
            queryFactory: $factory,
            cacheBuilder:GroupsCacheFactory::userGroups($userId),
            responseType: Group::class,
            requireObjectsList: true,
        );
    }

    /**
     * @param Group $dataObjectOrQueryFactory
     * @return void
     */
    public function deleteByGroup(
        Group $dataObjectOrQueryFactory,
    ): void
    {
        $this->delete(
            dataObjectOrQueryFactory: $dataObjectOrQueryFactory,
            cache: GroupsCacheFactory::group($dataObjectOrQueryFactory->getId())
        );
    }

    /**
     * @param Group $group
     * @return Group
     * @throws Exception
     */
    public function insert(
        Group $group,
    ): Group
    {
        return $this->data->create(
            queryFactory: $group,
            responseType: Group::class,
        );
    }
}