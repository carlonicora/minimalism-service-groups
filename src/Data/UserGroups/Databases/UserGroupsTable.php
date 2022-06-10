<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\UserGroups\Databases;

use CarloNicora\Minimalism\Interfaces\Sql\Attributes\SqlFieldAttribute;
use CarloNicora\Minimalism\Interfaces\Sql\Attributes\SqlTableAttribute;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\SqlFieldOption;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\SqlFieldType;

#[SqlTableAttribute(name: 'userGroups', databaseIdentifier: 'Groups')]
enum UserGroupsTable
{
    #[SqlFieldAttribute(fieldType: SqlFieldType::Integer,fieldOption: SqlFieldOption::PrimaryKey)]
    case userId;

    #[SqlFieldAttribute(fieldType: SqlFieldType::Integer,fieldOption: SqlFieldOption::PrimaryKey)]
    case groupId;
}