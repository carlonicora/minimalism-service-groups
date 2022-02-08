<?php
namespace CarloNicora\Minimalism\Services\Groups\DataObjects;

use CarloNicora\Minimalism\Interfaces\Sql\Attributes\DbField;
use CarloNicora\Minimalism\Interfaces\Sql\Attributes\DbTable;
use CarloNicora\Minimalism\Interfaces\Sql\Interfaces\SqlDataObjectInterface;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\Tables\GroupsTable;
use CarloNicora\Minimalism\Services\MySQL\Traits\SqlDataObjectTrait;
use CarloNicora\Minimalism\Services\ResourceBuilder\Interfaces\ResourceableDataInterface;

#[DbTable(tableClass: GroupsTable::class)]
class Group implements SqlDataObjectInterface, ResourceableDataInterface
{
    use SqlDataObjectTrait;

    /** @var int  */
    #[DbField]
    private int $id;

    /** @var string  */
    #[DbField]
    private string $name;

    /** @var bool  */
    #[DbField]
    private bool $canCreateGroups=false;

    /**
     * @return int
     */
    public function getId(
    ): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(
        int $id
    ): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(
    ): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(
        string $name,
    ): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function canCreateGroups(
    ): bool
    {
        return $this->canCreateGroups;
    }

    /**
     * @param bool $canCreateGroups
     */
    public function setCanCreateGroups(
        bool $canCreateGroups,
    ): void
    {
        $this->canCreateGroups = $canCreateGroups;
    }
}