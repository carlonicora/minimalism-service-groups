<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\Groups\Databases;


use CarloNicora\Minimalism\Interfaces\Sql\Attributes\SqlFieldAttribute;
use CarloNicora\Minimalism\Interfaces\Sql\Attributes\SqlTableAttribute;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\SqlFieldOption;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\SqlFieldType;

#[SqlTableAttribute(name: 'groupings', databaseIdentifier: 'Groups')]
enum GroupsTable
{
    #[SqlFieldAttribute(fieldType: SqlFieldType::Integer,fieldOption: SqlFieldOption::AutoIncrement)]
    case groupId;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String)]
    case name;

    #[SqlFieldAttribute(fieldType: SqlFieldType::Integer)]
    case canCreateGroups;
}