<?php
namespace CarloNicora\Minimalism\Services\Groups;

use CarloNicora\Minimalism\Abstracts\AbstractService;
use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheBuilderFactoryInterface;
use CarloNicora\Minimalism\Services\DataMapper\DataMapper;
use CarloNicora\Minimalism\Services\Groups\Data\Group;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\IO\GroupIO;
use CarloNicora\Minimalism\Services\Groups\IO\UserIO;
use Exception;

class Groups extends AbstractService
{
    /** @var CacheBuilderFactoryInterface  */
    protected CacheBuilderFactoryInterface $cacheBuilder;

    /**
     * @param DataMapper $mapper
     */
    public function __construct(
        private DataMapper $mapper,
    )
    {
        $this->cacheBuilder = new GroupsCacheFactory();
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
     * @throws Exception
     */
    public function getUserGroups(
        int $userId,
    ): array
    {
        /** @var GroupIO $groupIO */
        $groupIO = $this->objectFactory->create(GroupIO::class);

        return $groupIO->readByUserId(
            userId: $userId,
        );
    }

    /**
     * @param int $userId
     * @param int $groupId
     * @return bool
     * @throws Exception
     */
    public function isInGroup(
        int $userId,
        int $groupId,
    ): bool
    {
        /** @var GroupIO $groupIO */
        $groupIO = $this->objectFactory->create(GroupIO::class);

        $groups = $groupIO->readByUserId(
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
     * @throws Exception
     */
    public function isUserAdmin(
        int $userId,
    ): bool
    {
        /** @var GroupIO $groupIO */
        $groupIO = $this->objectFactory->create(GroupIO::class);

        $groups = $groupIO->readByUserId(
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
     * @throws Exception
     */
    public function getGroupUsers(
        int $groupId,
    ): array
    {
        /** @var UserIO $userIO */
        $userIO = $this->objectFactory->create(UserIO::class);

        return $userIO->readByGroupId(
            groupId: $groupId,
        );
    }
}