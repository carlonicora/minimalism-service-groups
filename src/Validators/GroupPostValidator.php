<?php
namespace CarloNicora\Minimalism\Services\Groups\Validators;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Services\DataValidator\Abstracts\AbstractDataValidator;
use CarloNicora\Minimalism\Services\DataValidator\Enums\DataTypes;
use CarloNicora\Minimalism\Services\DataValidator\Objects\AttributeValidator;
use CarloNicora\Minimalism\Services\DataValidator\Objects\DocumentValidator;
use CarloNicora\Minimalism\Services\DataValidator\Objects\ResourceValidator;
use CarloNicora\Minimalism\Services\Groups\Data\Groups\IO\GroupIO;
use Exception;
use RuntimeException;

class GroupPostValidator extends AbstractDataValidator
{
    /**
     *
     */
    public function __construct(
        private readonly GroupIO $groupIO,
    )
    {
        $this->documentValidator = new DocumentValidator();

        $resourceValidator = new ResourceValidator(
            type: 'group',
            isIdRequired: false,
            isSingleResource: true,
        );

        $resourceValidator->addAttributeValidator(
            new AttributeValidator(
                name:'name',
                isRequired: true,
            ),
        );

        $resourceValidator->addAttributeValidator(
            new AttributeValidator(
                name:'canCreateGroups',
                isRequired: true,
                type: DataTypes::bool
            ),
        );

        $this->documentValidator->addResourceValidator(
            validator: $resourceValidator
        );
    }

    /**
     * @return bool
     */
    public function validateData(
    ): bool
    {
        try {
            /** @noinspection UnusedFunctionResultInspection */
            $this->groupIO->readByGroupName($this->getDocument()->resources[0]->attributes->get('name'));
            throw new RuntimeException('Group name already in use', HttpCode::Conflict->value);
        } catch (Exception) {
        }

        return true;
    }
}