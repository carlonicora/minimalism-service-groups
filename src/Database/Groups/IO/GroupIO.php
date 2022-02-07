<?php
namespace CarloNicora\Minimalism\Services\Groups\Database\Groups\IO;

use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Interfaces\Sql\Abstracts\AbstractSqlIO;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Caches\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables\GroupsTable;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables\UserGroupsTable;
use CarloNicora\Minimalism\Services\Groups\DataObjects\Group;
use CarloNicora\Minimalism\Services\MySQL\Factories\SqlFactory;
use CarloNicora\Minimalism\Services\MySQL\Factories\SqlJoinFactory;
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
        $factory = SqlFactory::create(GroupsTable::class)
            ->selectAll();

        return $this->data->read(
            factory: $factory,
            sqlObjectInterfaceClass: Group::class,
            expectsSingleRecord: false,
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
        $factory = SqlFactory::create(GroupsTable::class)
            ->selectAll()
            ->addParameter(GroupsTable::groupId, $groupId);

        return $this->data->read(
            factory: $factory,
            cacheBuilder: GroupsCacheFactory::group($groupId),
            sqlObjectInterfaceClass: Group::class,
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
        $factory = SqlFactory::create(GroupsTable::class)
            ->selectAll()
            ->addParameter(GroupsTable::name, $name);

        return $this->data->read(
            factory: $factory,
            sqlObjectInterfaceClass: Group::class,
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
        $factory = SqlFactory::create(GroupsTable::class)
            ->selectAll()
            ->addJoin(new SqlJoinFactory(GroupsTable::groupId, UserGroupsTable::groupId))
            ->addParameter(UserGroupsTable::userId, $userId);

        return $this->data->read(
            factory: $factory,
            cacheBuilder:GroupsCacheFactory::userGroups($userId),
            sqlObjectInterfaceClass: Group::class,
            expectsSingleRecord: false,
        );
    }

    /**
     * @param int $groupId
     * @return void
     * @throws MinimalismException
     */
    public function delete(
        int $groupId,
    ): void
    {
        $factory = SqlFactory::create(GroupsTable::class)
            ->delete()
            ->addParameter(GroupsTable::groupId, $groupId);

        $this->data->delete(
            factory: $factory,
            cacheBuilder: GroupsCacheFactory::group($groupId)
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
            factory: $group,
            sqlObjectInterfaceClass: Group::class,
        );
    }
}