<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;


use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;

class HandleUseCaseRule
{
    private $namespace;
    private $target;

    public function __construct(string $namespace, string $target)
    {
        $this->namespace = $namespace;
        $this->target = $target;
    }

    public function apply(): void
    {
        try {
            $contents = file_get_contents(__DIR__ . '/../Templates/UseCase.template');
            $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);

            file_put_contents("{$this->target}/UseCase.php", $contents);
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error generating use case', 1, $exception);
        }
    }
}
