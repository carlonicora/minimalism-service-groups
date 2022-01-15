<?php
namespace CarloNicora\Minimalism\Services\Groups\Builders;

use CarloNicora\Minimalism\Services\Builder\Abstracts\AbstractResourceBuilder;
use Exception;

class GroupBuilder extends AbstractResourceBuilder
{
    /** @var string  */
    public string $type = 'group';

    /**
     * @param array $data
     * @throws Exception
     */
    public function setAttributes(
        array $data
    ): void
    {
        $this->response->id = $this->encrypter->encryptId($data['streamId']);
        $this->response->attributes->add(name: 'name', value: $data['name']);
        $this->response->attributes->add(name: 'canCreateGroups', value: $data['canCreateGroups']);
    }
}