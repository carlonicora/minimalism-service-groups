<?php
namespace CarloNicora\Minimalism\Services\Groups\IO;

use CarloNicora\Minimalism\Services\Groups\Abstracts\AbstractGroupsIO;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables\UserGroupsTable;

class UserIO extends AbstractGroupsIO
{
    /**
     * @param int $groupId
     * @return array
     */
    public function readByGroupId(
        int $groupId,
    ): array
    {
        $response = [];

        /** @see UserGroupsTable::readGroupUsers() */
        $recordset = $this->data->read(
            tableInterfaceClassName: UserGroupsTable::class,
            functionName: 'readGroupUsers',
            parameters: [$groupId],
            cacheBuilder: $this->cacheFactory->groupUsers($groupId),
        );

        foreach ($recordset ?? [] as $record){
            $response[] = $record['userId'];
        }

        return $response;
    }
}