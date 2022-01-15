<?php
namespace CarloNicora\Minimalism\Services\Groups\Abstracts;

use CarloNicora\Minimalism\Abstracts\AbstractModel;
use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Factories\MinimalismFactories;
use CarloNicora\Minimalism\Interfaces\User\Interfaces\UserServiceInterface;
use CarloNicora\Minimalism\Services\Groups\IO\GroupIO;
use CarloNicora\Minimalism\Services\Groups\IO\UserIO;
use Exception;
use RuntimeException;

abstract class AbstractGroupModel extends AbstractModel
{
    /** @var UserServiceInterface  */
    private UserServiceInterface $currentUser;

    /**
     * @param MinimalismFactories $minimalismFactories
     * @param string|null $function
     */
    public function __construct(
        MinimalismFactories $minimalismFactories,
        ?string $function = null,
    )
    {
        parent::__construct($minimalismFactories,$function);

        $this->currentUser = $minimalismFactories->getServiceFactory()->create(UserServiceInterface::class);

        if ($this->currentUser->isVisitor()){
            throw new RuntimeException('Unauthorized', HttpCode::Unauthorized->value);
        }

        try {
            $this->currentUser->getId();
        } catch (Exception) {
            throw new RuntimeException('Unauthorized', HttpCode::Unauthorized->value);
        }
    }

    /**
     * @param int $groupId
     * @return void
     * @throws Exception
     */
    protected function validateBearerGroupBelonging(
        int $groupId
    ): void
    {
        if (!$this->objectFactory->create(UserIO::class)->doesUserBelongsToGroup($this->currentUser->getId(), $groupId)){
            throw new RuntimeException('Forbidden', HttpCode::Forbidden->value);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function validateBearerGroupCrationCapabilities(
    ): void
    {
        foreach($this->objectFactory->create(GroupIO::class)->readByUserId($this->currentUser->getId()) as $group) {
            if ($group->canCreateGroups()){
                return;
            }
        }

        throw new RuntimeException('Forbidden', HttpCode::Forbidden->value);
    }
}