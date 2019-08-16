<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;


use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;

class CreateFoldersRule
{
    private $target;

    public function __construct(string $target)
    {
        $this->target = $target;
    }

    public function apply(): void
    {
        try {
            mkdir("$this->target/Builders");
            mkdir("$this->target/Entities");
            mkdir("$this->target/Exceptions");
            mkdir("$this->target/Gateways");
            mkdir("$this->target/Requests");
            mkdir("$this->target/Responses");
            mkdir("$this->target/Responses/Error");
            mkdir("$this->target/Rules");
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error creating directories', 1, $exception);
        }
    }
}
