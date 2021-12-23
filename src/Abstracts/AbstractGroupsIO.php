<?php
namespace CarloNicora\Minimalism\Services\Groups\Abstracts;

use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheBuilderFactoryInterface;
use CarloNicora\Minimalism\Interfaces\ServiceInterface;
use CarloNicora\Minimalism\Services\DataMapper\Abstracts\AbstractLoader;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\Groups;

abstract class AbstractGroupsIO extends AbstractLoader
{
    /** @var CacheBuilderFactoryInterface|GroupsCacheFactory|null  */
    protected CacheBuilderFactoryInterface|GroupsCacheFactory|null $cacheFactory;

    /** @var ServiceInterface|Groups|null  */
    protected ServiceInterface|Groups|null $defaultService;

}