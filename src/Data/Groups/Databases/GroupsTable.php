<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\Groups\Databases;

use CarloNicora\Minimalism\Services\MySQL\Data\SqlField;
use CarloNicora\Minimalism\Services\MySQL\Data\SqlTable;
use CarloNicora\Minimalism\Services\MySQL\Enums\FieldOption;
use CarloNicora\Minimalism\Services\MySQL\Enums\FieldType;

#[SqlTable(name: 'groupings', databaseIdentifier: 'Groups')]
enum GroupsTable
{
    #[SqlField(fieldType: FieldType::Integer,fieldOption: FieldOption::AutoIncrement)]
    case groupId;

    #[SqlField]
    case name;

    #[SqlField(fieldType: FieldType::Integer)]
    case canCreateGroups;
}