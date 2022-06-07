<?php
namespace CarloNicora\Minimalism\Services\Groups\Models;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Interfaces\Encrypter\Parameters\PositionedEncryptedParameter;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\Builders\GroupBuilder;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\DataObjects\Group;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\IO\GroupIO;
use CarloNicora\Minimalism\Services\Groups\Data\Users\IO\UserIO;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\Models\Abstracts\AbstractGroupModel;
use CarloNicora\Minimalism\Services\Groups\Validators\GroupPostValidator;
use CarloNicora\Minimalism\Services\ResourceBuilder\ResourceBuilder;
use Exception;

class Groups extends AbstractGroupModel
{
    /**
     * @param ResourceBuilder $builder
     * @param PositionedEncryptedParameter|null $groupId
     * @return HttpCode
     * @throws Exception
     */
    public function get(
        ResourceBuilder $builder,
        ?PositionedEncryptedParameter $groupId=null,
    ): HttpCode
    {
        if ($groupId !== null) {
            $group = $this->objectFactory->create(GroupIO::class)->readByGroupId($groupId->getValue());

            $this->document->addResource(
                resource: $builder->buildResource(
                    builderClass: GroupBuilder::class,
                    data: $group,
                    cacheBuilder: GroupsCacheFactory::group($groupId->getValue()),
                ),
            );
        } else {
            $groups = $this->objectFactory->create(GroupIO::class)->readAll();

            /** @see GroupIO::readAll() */
            $this->document->addResourceList(
                resourceList:$builder->buildResources(
                    builderClass: GroupBuilder::class,
                    data: $groups,
                ),
            );
        }

        return HttpCode::Ok;
    }

    /**
     * @param PositionedEncryptedParameter $groupId
     * @return HttpCode
     * @throws Exception
     */
    public function delete(
        PositionedEncryptedParameter $groupId,
    ): HttpCode
    {
        $group = $this->objectFactory->create(GroupIO::class)->readByGroupId($groupId->getValue());

        $this->validateBearerGroupBelonging($group->getId());

        $this->objectFactory->create(GroupIO::class)->delete($group);
        $this->objectFactory->create(UserIO::class)->deleteByGroupId($group->getId());

        return HttpCode::NoContent;
    }

    /**
     * @param ResourceBuilder $builder
     * @param GroupPostValidator $payload
     * @return HttpCode
     * @throws Exception
     */
    public function post(
        ResourceBuilder $builder,
        GroupPostValidator $payload,
    ): HttpCode
    {
        $this->validateBearerGroupCrationCapabilities();

        $group = $builder->ingestResource(
            dataClass: GroupBuilder::class,
            resource: $payload->getSingleResource()
        );

        $group = $this->objectFactory->create(GroupIO::class)->insert($group);

        $this->document->addResource(
            resource: $builder->buildResource(
                builderClass: GroupBuilder::class,
                data: $group,
            ),
        );

        return HttpCode::Created;
    }
}