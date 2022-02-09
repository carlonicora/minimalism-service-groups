<?php
namespace CarloNicora\Minimalism\Services\Groups\Models\Groups;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Interfaces\Encrypter\Parameters\PositionedEncryptedParameter;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\IO\GroupIO;
use CarloNicora\Minimalism\Services\Groups\Data\Users\Builders\UserBuilders;
use CarloNicora\Minimalism\Services\Groups\Data\Users\IO\UserIO;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\Models\Abstracts\AbstractGroupModel;
use CarloNicora\Minimalism\Services\ResourceBuilder\ResourceBuilder;
use Exception;

class Users extends AbstractGroupModel
{
    /**
     * @param ResourceBuilder $builder
     * @param PositionedEncryptedParameter $groupId
     * @return HttpCode
     * @throws Exception
     */
    public function get(
        ResourceBuilder $builder,
        PositionedEncryptedParameter $groupId,
    ): HttpCode
    {
        $users = $this->objectFactory->create(UserIO::class)->readByGroupId($groupId->getValue());

        $this->document->addResourceList(
            resourceList: $builder->buildResources(
                builderClass: UserBuilders::class,
                data: $users,
                cacheBuilder: GroupsCacheFactory::groupUsers($groupId->getValue()),
            ),
        );

        if (count($this->document->resources) === 0){
            return HttpCode::NoContent;
        }

        return HttpCode::Ok;
    }

    /**
     * @param PositionedEncryptedParameter $groupId
     * @param PositionedEncryptedParameter $userId
     * @return HttpCode
     * @throws Exception
     */
    public function post(
        PositionedEncryptedParameter $groupId,
        PositionedEncryptedParameter $userId,
    ): HttpCode
    {
        $group = $this->objectFactory->create(GroupIO::class)->readByGroupId($groupId->getValue());
        $this->validateBearerGroupBelonging($group->getId());

        $this->objectFactory->create(UserIO::class)->insert(
            userId: $userId->getValue(),
            groupId: $group->getId(),
        );

        return HttpCode::Created;
    }

    /**
     * @param PositionedEncryptedParameter $groupId
     * @param PositionedEncryptedParameter $userId
     * @return HttpCode
     * @throws Exception
     */
    public function delete(
        PositionedEncryptedParameter $groupId,
        PositionedEncryptedParameter $userId,
    ): HttpCode
    {
        $group = $this->objectFactory->create(GroupIO::class)->readByGroupId($groupId->getValue());
        $this->validateBearerGroupBelonging($group->getId());

        if (!$this->objectFactory->create(UserIO::class)->doesUserBelongsToGroup($userId->getValue(), $group->getId())) {
            return HttpCode::PreconditionFailed;
        }

        $this->objectFactory->create(UserIO::class)->delete(
            userId: $userId->getValue(),
            groupId: $group->getId(),
        );

        return HttpCode::NoContent;
    }
}