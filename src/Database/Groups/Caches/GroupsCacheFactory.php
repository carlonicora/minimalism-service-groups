<?php
namespace CarloNicora\Minimalism\Services\Groups\Database\Groups\Caches;

use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheBuilderInterface;
use CarloNicora\Minimalism\Services\Cacher\Factories\CacheBuilderFactory;

class GroupsCacheFactory extends CacheBuilderFactory
{
    /**
     * @param int $groupId
     * @return CacheBuilderInterface
     */
    public static function group(
        int $groupId,
    ): CacheBuilderInterface
    {
        return self::create(
            cacheName: 'groupId',
            identifier: $groupId,
        );
    }

    /**
     * @param int $userId
     * @return CacheBuilderInterface
     */
    public static function userGroups(
        int $userId,
    ): CacheBuilderInterface
    {
        return self::createList(
            listName: 'groupId',
            cacheName: 'userId',
            identifier: $userId,
        );
    }

    /**
     * @param int $groupId
     * @return CacheBuilderInterface
     */
    public static function groupUsers(
        int $groupId,
    ): CacheBuilderInterface
    {
        return self::createList(
            listName: 'userId',
            cacheName: 'groupId',
            identifier: $groupId,
        );
    }
}