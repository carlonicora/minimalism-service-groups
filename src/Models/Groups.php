<?php
namespace CarloNicora\Minimalism\Services\Groups\Models;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Interfaces\Data\Interfaces\DataFunctionInterface;
use CarloNicora\Minimalism\Interfaces\Data\Objects\DataFunction;
use CarloNicora\Minimalism\Interfaces\Encrypter\Parameters\PositionedEncryptedParameter;
use CarloNicora\Minimalism\Services\Builder\Builder;
use CarloNicora\Minimalism\Services\Groups\Abstracts\AbstractGroupModel;
use CarloNicora\Minimalism\Services\Groups\Builders\GroupBuilder;
use CarloNicora\Minimalism\Services\Groups\Data\Group;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\IO\GroupIO;
use CarloNicora\Minimalism\Services\Groups\IO\UserIO;
use CarloNicora\Minimalism\Services\Groups\Validators\GroupPostValidator;
use Exception;

class Groups extends AbstractGroupModel
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
        /** @see GroupIO::readByGroupId() */
        $this->document->addResource(
            resource: current(
                $builder->build(
                    resourceTransformerClass: GroupBuilder::class,
                    function: new DataFunction(
                        type: DataFunctionInterface::TYPE_LOADER,
                        className: GroupIO::class,
                        functionName: 'readByGroupId',
                        parameters: [$groupId->getValue()],
                        cacheBuilder: GroupsCacheFactory::group($groupId->getValue()),
                    ),
                ),
            ),
        );

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

        $this->objectFactory->create(GroupIO::class)->delete($group->getId());
        $this->objectFactory->create(UserIO::class)->deleteByGroupId($group->getId());

        return HttpCode::NoContent;
    }

    /**
     * @param Builder $builder
     * @param GroupPostValidator $payload
     * @return HttpCode
     * @throws Exception
     */
    public function post(
        Builder $builder,
        GroupPostValidator $payload,
    ): HttpCode
    {
        $this->validateBearerGroupCrationCapabilities();

        $group = new Group($this->objectFactory);
        $group->ingestResource($payload->getDocument()->resources[0]);

        $group = $this->objectFactory->create(GroupIO::class)->insert($group);

        $this->document->addResource(
            resource: current(
                $builder->buildByData(
                    resourceTransformerClass: GroupBuilder::class,
                    data: [$group->export()],
                ),
            ),
        );

        return HttpCode::Created;
    }
}