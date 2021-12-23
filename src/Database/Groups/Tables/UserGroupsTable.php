<?php
namespace CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables;

use CarloNicora\Minimalism\Services\MySQL\Abstracts\AbstractMySqlTable;
use CarloNicora\Minimalism\Services\MySQL\Interfaces\FieldInterface;
use Exception;

class UserGroupsTable extends AbstractMySqlTable
{
    /** @var string  */
    protected static string $tableName = 'photoshoots';

    /** @var array  */
    protected static array $fields = [
        'userId'    => FieldInterface::INTEGER
                    +  FieldInterface::PRIMARY_KEY,
        'groupId'   => FieldInterface::INTEGER
                    +  FieldInterface::PRIMARY_KEY,
    ];

    /**
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function readUserGroups(
        int $userId,
    ): array{
        $this->sql = 'SELECT * '
            . ' FROM ' . self::getTableName()
            . ' WHERE userId=?';
        $this->parameters = ['s', $userId];

        return $this->functions->runRead();
    }

    /**
     * @param int $groupId
     * @return array
     * @throws Exception
     */
    public function readGroupUsers(
        int $groupId,
    ): array{
        $this->sql = 'SELECT * '
            . ' FROM ' . self::getTableName()
            . ' WHERE groupId=?';
        $this->parameters = ['s', $groupId];

        return $this->functions->runRead();
    }
}