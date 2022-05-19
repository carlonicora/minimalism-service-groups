<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\Users\Builders;

use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\DataObjects\Group;
use CarloNicora\Minimalism\Services\ResourceBuilder\Abstracts\AbstractResourceBuilder;
use CarloNicora\Minimalism\Services\ResourceBuilder\Interfaces\ResourceableDataInterface;
use Exception;

class UserBuilders extends AbstractResourceBuilder
{
    /**
     * @param Group $data
     * @return ResourceObject
     * @throws Exception
     */
    public function buildResource(
        ResourceableDataInterface $data,
    ): ResourceObject
    {
        return new ResourceObject(
            type: 'user',
            id: $this->encrypter !== null ? $this->encrypter->encryptId($data->getId()) : $data->getId(),
        );
    }
}