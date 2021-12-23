<?php
namespace CarloNicora\Minimalism\Services\Groups\IO;

use CarloNicora\Minimalism\Services\Groups\Abstracts\AbstractGroupsIO;
use CarloNicora\Minimalism\Services\Groups\Data\Group;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables\UserGroupsTable;

class GroupIO extends AbstractGroupsIO
{
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
                cacheBuilder: $this->cacheFactory->userGroups($userId),
            ),
            objectType: Group::class,
        );
    }
}