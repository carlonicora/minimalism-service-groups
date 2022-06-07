<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\Groups\Builders;

use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Factories\ObjectFactory;
use CarloNicora\Minimalism\Interfaces\Encrypter\Interfaces\EncrypterInterface;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\DataObjects\Group;
use CarloNicora\Minimalism\Services\ResourceBuilder\Abstracts\AbstractResourceBuilder;
use CarloNicora\Minimalism\Services\ResourceBuilder\Interfaces\ResourceableDataInterface;
use Exception;

class GroupBuilder extends AbstractResourceBuilder
{
    /**
     * @param ObjectFactory|null $objectFactory
     * @param EncrypterInterface $encrypter
     */
    public function __construct(
        private readonly ?ObjectFactory       $objectFactory,
        protected readonly EncrypterInterface $encrypter,
    )
    {
        parent::__construct($this->encrypter);
    }

    /**
     * @param Group $data
     * @return ResourceObject
     * @throws Exception
     */
    public function buildResource(
        ResourceableDataInterface $data,
    ): ResourceObject
    {
        $response = new ResourceObject(
            type: 'group',
            id: $this->encrypter !== null ? $this->encrypter->encryptId($data->getId()) : $data->getId(),
        );
        $response->attributes->add(name: 'name', value: $data->getName());
        $response->attributes->add(name: 'canCreateGroups', value: $data->canCreateGroups());

        return $response;
    }

    /**
     * @param ResourceObject $resource
     * @param ResourceableDataInterface|null $dataObject
     * @return Group
     * @throws Exception
     */
    public function ingestResource(
        ResourceObject $resource,
        ?ResourceableDataInterface $dataObject,
    ): ResourceableDataInterface
    {
        $response = $this->objectFactory->create(Group::class);

        if ($resource->id !== null){
            $response->setId($this->encrypter !== null ? $this->encrypter->decryptId($resource->id) : (int)$resource->id);
        }
        $response->setCanCreateGroups($resource->attributes->has('canCreateGroups') ? $resource->attributes->get('canCreateGroups') : false);

        return $response;
    }
}