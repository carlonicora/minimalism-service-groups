<?php
namespace CarloNicora\Minimalism\Services\Groups\Builders;

use CarloNicora\Minimalism\Services\Builder\Abstracts\AbstractResourceBuilder;
use Exception;

class UserBuilder extends AbstractResourceBuilder
{
    /** @var string  */
    public string $type = 'user';

    /**
     * @param array $data
     * @throws Exception
     */
    public function setAttributes(
        array $data
    ): void
    {
        $this->response->id = $this->encrypter->encryptId($data['userId']);
    }
}