<?php
namespace CarloNicora\Minimalism\Services\Groups\Data\Users\Builders;

use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Services\Groups\Data\Users\DataObjects\User;
use CarloNicora\Minimalism\Services\ResourceBuilder\Abstracts\AbstractResourceBuilder;
use CarloNicora\Minimalism\Services\ResourceBuilder\Interfaces\ResourceableDataInterface;
use Exception;

class UserBuilders extends AbstractResourceBuilder
{

    /**
     * @param User|ResourceableDataInterface $data
     * @return ResourceObject
     * @throws Exception
     */
    public function buildResource(
        User|ResourceableDataInterface $data,
    ): ResourceObject
    {
        return new ResourceObject(
            type: 'user',
            id: $this->encrypter->encryptId($data->getId()),
        );
    }
}