<?php
namespace CarloNicora\Minimalism\Services\Groups;

use CarloNicora\Minimalism\Abstracts\AbstractService;
use CarloNicora\Minimalism\Services\Groups\Data\Group;
use CarloNicora\Minimalism\Services\Groups\IO\GroupIO;
use CarloNicora\Minimalism\Services\Groups\IO\UserIO;
use Exception;

class Groups extends AbstractService
{
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

        $users = $userIO->readByGroupId(
            groupId: $groupId,
        );

        $response = [];
        foreach ($users as $user){
            $response[] = $user['userId'];
        }

        return $response;
    }
}