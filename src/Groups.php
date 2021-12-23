<?php
namespace CarloNicora\Minimalism\Services\Groups;

use CarloNicora\Minimalism\Abstracts\AbstractService;
use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheBuilderFactoryInterface;
use CarloNicora\Minimalism\Services\DataMapper\DataMapper;
use CarloNicora\Minimalism\Services\Groups\Data\Group;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\IO\GroupIO;
use CarloNicora\Minimalism\Services\Groups\IO\UserIO;

class Groups extends AbstractService
{
    /** @var CacheBuilderFactoryInterface  */
    protected CacheBuilderFactoryInterface $cacheBuilder;

    /**
     * @param DataMapper $mapper
     * @param GroupIO $groupIO
     * @param UserIO $userIO
     */
    public function __construct(
        private DataMapper $mapper,
        private GroupIO    $groupIO,
        private UserIO     $userIO,
    )
    {
        $this->cacheBuilder = new GroupsCacheFactory();
        parent::__construct();
    }

    /**
     * @return void
     */
    public function initialise(): void
    {
        $this->mapper->setCacheFactory($this->cacheBuilder);
        $this->mapper->setDefaultService($this);
    }

    /**
     * @param int $userId
     * @return Group[]
     */
    public function getUserGroups(
        int $userId,
    ): array
    {
        return $this->groupIO->readByUserId(
            userId: $userId,
        );
    }

    /**
     * @param int $userId
     * @param int $groupId
     * @return bool
     */
    public function isInGroup(
        int $userId,
        int $groupId,
    ): bool
    {
        $groups = $this->groupIO->readByUserId(
            userId: $userId,
        );

        foreach ($groups ?? [] as $group){
            if ($group->getGroupId() === $groupId){
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function isUserAdmin(
        int $userId,
    ): bool
    {
        $groups = $this->groupIO->readByUserId(
            userId: $userId,
        );

        foreach ($groups ?? [] as $group){
            if ($group->isAdminGroup()){
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $groupId
     * @return array
     */
    public function getGroupUsers(
        int $groupId,
    ): array
    {
        return $this->userIO->readByGroupId(
            groupId: $groupId,
        );
    }
}