<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;

use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;

class HandleExceptionsRule
{
    private $namespace;
    private $target;
    private $exceptions;

    public function __construct(string $namespace, string $target, array $exceptions)
    {
        $this->namespace = $namespace;
        $this->target = $target;
        $this->exceptions = $exceptions;
    }

    public function apply(): void
    {
        try {
            foreach ($this->exceptions as $exception) {
                $contents = file_get_contents(__DIR__ . '/../Templates/Exceptions/Exception.template');
                $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);
                $contents = preg_replace("/\{LESCRIPT_EXCEPTION_NAME\}/", $exception, $contents);

                file_put_contents("{$this->target}/Exceptions/{$exception}.php", $contents);
            }
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error generating exceptions', 1, $exception);
        }
    }
}
