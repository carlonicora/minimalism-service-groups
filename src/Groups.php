<?php
namespace CarloNicora\Minimalism\Services\Groups;

use CarloNicora\Minimalism\Abstracts\AbstractService;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\IO\GroupIO;
use CarloNicora\Minimalism\Services\Groups\Database\Groups\IO\UserIO;
use CarloNicora\Minimalism\Services\Groups\DataObjects\Group;
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
        return $this->objectFactory->create(GroupIO::class)->readByUserId(
            userId: $userId,
        );
    }

    /**
     * @param int $userId
     * @param array $groupIds
     * @return bool
     * @throws Exception
     */
    public function isInGroups(
        int $userId,
        array $groupIds,
    ): bool
    {
        foreach ($this->getUserGroups($userId) as $group){
            if (in_array($group->getId(), $groupIds, true)){
                return true;
            }
        }

        return false;
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
        $groups = $this->objectFactory->create(GroupIO::class)->readByUserId(
            userId: $userId,
        );

        /** @var Group $group */
        foreach ($groups ?? [] as $group){
            if ($group->getId() === $groupId){
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
        $users = $this->objectFactory->create(UserIO::class)->readByGroupId(
            groupId: $groupId,
        );

        $response = [];
        foreach ($users as $user){
            $response[] = $user['userId'];
        }

        return $response;
    }
}