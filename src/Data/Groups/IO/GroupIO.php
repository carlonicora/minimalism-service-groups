<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\Groups\IO;

use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheBuilderInterface;
use CarloNicora\Minimalism\Interfaces\Sql\Abstracts\AbstractSqlIO;
use CarloNicora\Minimalism\Interfaces\Sql\Interfaces\SqlDataObjectInterface;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\Databases\GroupsTable;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\DataObjects\Group;
use CarloNicora\Minimalism\Services\Groups\Data\UserGroups\Databases\UserGroupsTable;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\MySQL\Factories\SqlJoinFactory;
use CarloNicora\Minimalism\Services\MySQL\Factories\SqlQueryFactory;
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
        $factory = SqlQueryFactory::create(GroupsTable::class)
            ->selectAll();

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
            ->selectAll()
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
            ->selectAll()
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
            ->selectAll()
            ->addJoin(new SqlJoinFactory(GroupsTable::groupId, UserGroupsTable::groupId))
            ->addParameter(UserGroupsTable::userId, $userId);

        return $this->data->read(
            queryFactory: $factory,
            cacheBuilder:GroupsCacheFactory::userGroups($userId),
            responseType: Group::class,
            requireObjectsList: true,
        );
    }

    /**
     * @param Group $dataObject
     * @param CacheBuilderInterface|null $cache
     * @return void
     */
    public function delete(
        SqlDataObjectInterface $dataObject,
        ?CacheBuilderInterface $cache = null
    ): void
    {
        parent::delete(
            dataObject: $dataObject,
            cache: GroupsCacheFactory::group($dataObject->getId())
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