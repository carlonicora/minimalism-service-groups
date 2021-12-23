<?php

namespace CarloNicora\Minimalism\Services\Groups\Factories;

use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheBuilderInterface;
use CarloNicora\Minimalism\Services\Cacher\Factories\CacheBuilderFactory;

class GroupsCacheFactory extends CacheBuilderFactory
{
    /**
     * @param int $userId
     * @return CacheBuilderInterface
     */
    public function userGroups(
        int $userId,
    ): CacheBuilderInterface
    {
        return $this->createList(
            listName: 'groupId',
            cacheName: 'userId',
            identifier: $userId,
        );
    }

    /**
     * @param int $groupId
     * @return CacheBuilderInterface
     */
    public function groupUsers(
        int $groupId,
    ): CacheBuilderInterface
    {
        return $this->createList(
            listName: 'userId',
            cacheName: 'groupId',
            identifier: $groupId,
        );
    }
}