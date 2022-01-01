<?php
namespace CarloNicora\Minimalism\Services\Groups\Abstracts;

use CarloNicora\Minimalism\Factories\ObjectFactory;
use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheBuilderFactoryInterface;
use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheInterface;
use CarloNicora\Minimalism\Interfaces\Data\Interfaces\DataInterface;
use CarloNicora\Minimalism\Interfaces\ServiceInterface;
use CarloNicora\Minimalism\Services\DataMapper\Abstracts\AbstractLoader;
use CarloNicora\Minimalism\Services\DataMapper\DataMapper;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\Groups;

abstract class AbstractGroupsIO extends AbstractLoader
{
    /** @var CacheBuilderFactoryInterface|GroupsCacheFactory|null  */
    protected CacheBuilderFactoryInterface|GroupsCacheFactory|null $cacheFactory;

    /** @var ServiceInterface|Groups|null  */
    protected ServiceInterface|Groups|null $defaultService;


    /**
     * @param ObjectFactory $objectFactory
     * @param DataMapper $mapper
     * @param DataInterface $data
     * @param CacheInterface|null $cache
     */
    public function __construct(
        protected ObjectFactory $objectFactory,
        protected DataMapper $mapper,
        protected DataInterface $data,
        protected ?CacheInterface $cache,
    )
    {
        parent::__construct(
            objectFactory: $objectFactory,
            mapper: $mapper,
            data: $data,
            cache: $cache,
        );

        $this->cacheFactory = new GroupsCacheFactory();
    }
}