<?php
namespace CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables;

use CarloNicora\Minimalism\Services\MySQL\Abstracts\AbstractMySqlTable;
use CarloNicora\Minimalism\Services\MySQL\Interfaces\FieldInterface;
use Exception;

class GroupsTable extends AbstractMySqlTable
{
    /** @var string  */
    protected static string $tableName = 'groupings';

    /** @var array  */
    protected static array $fields = [
        'groupId'           => FieldInterface::INTEGER
                            +  FieldInterface::PRIMARY_KEY
                            +  FieldInterface::AUTO_INCREMENT,
        'name'              => FieldInterface::STRING,
        'canCreateGroups'   => FieldInterface::INTEGER,
    ];

    /**
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function readByGroupName(
        string $name,
    ): array
    {
        $this->sql = 'SELECT *'
            . ' FROM ' . self::getTableName()
            . ' WHERE name=?;';
        $this->parameters = ['s', $name];

        return $this->functions->runRead();
    }

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
            . ' JOIN ' . UserGroupsTable::getTableName()
            . ' ON ' . self::getTableName() . '.groupId=' . UserGroupsTable::getTableName() . '.groupId'
            . ' WHERE ' . UserGroupsTable::getTableName() . '.userId=?';
        $this->parameters = ['i', $userId];

        return $this->functions->runRead();
    }
}