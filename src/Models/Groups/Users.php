<?php
namespace CarloNicora\Minimalism\Services\Groups\Models\Groups;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Interfaces\Data\Interfaces\DataFunctionInterface;
use CarloNicora\Minimalism\Interfaces\Data\Objects\DataFunction;
use CarloNicora\Minimalism\Interfaces\Encrypter\Parameters\PositionedEncryptedParameter;
use CarloNicora\Minimalism\Services\Builder\Builder;
use CarloNicora\Minimalism\Services\Groups\Abstracts\AbstractGroupModel;
use CarloNicora\Minimalism\Services\Groups\Builders\UserBuilder;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\IO\UserIO;
use Exception;

class Users extends AbstractGroupModel
{
    /**
     * @param Builder $builder
     * @param PositionedEncryptedParameter $groupId
     * @return HttpCode
     * @throws Exception
     */
    public function get(
        Builder $builder,
        PositionedEncryptedParameter $groupId,
    ): HttpCode
    {
        /** @see UserIO::readByGroupId() */
        $this->document->addResourceList(
            resourceList: $builder->build(
                resourceTransformerClass: UserBuilder::class,
                function: new DataFunction(
                    type: DataFunctionInterface::TYPE_LOADER,
                    className: UserIO::class,
                    functionName: 'readByGroupId',
                    parameters: [$groupId->getValue()],
                    cacheBuilder: GroupsCacheFactory::groupUsers($groupId->getValue()),
                ),
            ),
        );

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
        $this->validateBearerGroupBelonging($groupId->getValue());

        $this->objectFactory->create(UserIO::class)->insert(
            userId: $userId->getValue(),
            groupId: $groupId->getValue(),
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
        $this->validateBearerGroupBelonging($groupId->getValue());

        $this->objectFactory->create(UserIO::class)->delete(
            userId: $userId->getValue(),
            groupId: $groupId->getValue(),
        );

        return HttpCode::NoContent;
    }
}