<?php
namespace CarloNicora\Minimalism\Services\Groups\Models\Users;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Interfaces\Data\Interfaces\DataFunctionInterface;
use CarloNicora\Minimalism\Interfaces\Data\Objects\DataFunction;
use CarloNicora\Minimalism\Interfaces\Encrypter\Parameters\PositionedEncryptedParameter;
use CarloNicora\Minimalism\Services\Builder\Builder;
use CarloNicora\Minimalism\Services\Groups\Abstracts\AbstractGroupModel;
use CarloNicora\Minimalism\Services\Groups\Builders\GroupBuilder;
use CarloNicora\Minimalism\Services\Groups\Factories\GroupsCacheFactory;
use CarloNicora\Minimalism\Services\Groups\IO\GroupIO;
use Exception;

class Groups extends AbstractGroupModel
{
    /**
     * @param Builder $builder
     * @param PositionedEncryptedParameter $userId
     * @return HttpCode
     * @throws Exception
     */
    public function get(
        Builder $builder,
        PositionedEncryptedParameter $userId,
    ): HttpCode
    {
        /** @see GroupIO::readByUserId() */
        $this->document->addResourceList(
            resourceList: $builder->build(
                resourceTransformerClass: GroupBuilder::class,
                function: new DataFunction(
                    type: DataFunctionInterface::TYPE_LOADER,
                    className: GroupIO::class,
                    functionName: 'readByUserId',
                    parameters: [$userId->getValue()],
                    cacheBuilder: GroupsCacheFactory::userGroups($userId->getValue()),
                ),
            ),
        );

        return HttpCode::Ok;
    }
}