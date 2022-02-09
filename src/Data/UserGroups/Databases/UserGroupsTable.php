<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\UserGroups\Databases;

use CarloNicora\Minimalism\Services\MySQL\Data\SqlField;
use CarloNicora\Minimalism\Services\MySQL\Data\SqlTable;
use CarloNicora\Minimalism\Services\MySQL\Enums\FieldOption;
use CarloNicora\Minimalism\Services\MySQL\Enums\FieldType;

#[SqlTable(name: 'userGroups', databaseIdentifier: 'Groups')]
enum UserGroupsTable
{
    #[SqlField(fieldType: FieldType::Integer,fieldOption: FieldOption::PrimaryKey)]
    case userId;

    #[SqlField(fieldType: FieldType::Integer,fieldOption: FieldOption::PrimaryKey)]
    case groupId;
}