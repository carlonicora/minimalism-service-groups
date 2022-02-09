<?php
namespace CarloNicora\Minimalism\Services\Groups\Models\Users;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Interfaces\Encrypter\Parameters\PositionedEncryptedParameter;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\Builders\GroupBuilder;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\IO\GroupIO;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\Models\Abstracts\AbstractGroupModel;
use CarloNicora\Minimalism\Services\ResourceBuilder\ResourceBuilder;
use Exception;

class Groups extends AbstractGroupModel
{
    /**
     * @param ResourceBuilder $builder
     * @param PositionedEncryptedParameter $userId
     * @return HttpCode
     * @throws MinimalismException
     * @throws Exception
     */
    public function get(
        ResourceBuilder $builder,
        PositionedEncryptedParameter $userId,
    ): HttpCode
    {
        $groups = $this->objectFactory->create(GroupIO::class)->readByUserId($userId->getValue());

        $this->document->addResourceList(
            resourceList: $builder->buildResources(
                builderClass: GroupBuilder::class,
                data: $groups,
                cacheBuilder: GroupsCacheFactory::userGroups($userId->getValue()),
            ),
        );

        if (count($this->document->resources) === 0){
            return HttpCode::NoContent;
        }

        return HttpCode::Ok;
    }
}