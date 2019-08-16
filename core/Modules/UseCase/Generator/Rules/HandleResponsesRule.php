<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;

use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;

class HandleResponsesRule
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
            // Response
            $contents = file_get_contents(__DIR__ . '/../Templates/Responses/Response.template');
            $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);
            file_put_contents("{$this->target}/Responses/Response.php", $contents);

            // Error response
            $contents = file_get_contents(__DIR__ . '/../Templates/Responses/Error/Response.template');
            $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);
            file_put_contents("{$this->target}/Responses/Error/Response.php", $contents);

            // Response interface
            $contents = file_get_contents(__DIR__ . '/../Templates/Responses/ResponseInterface.template');
            $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);
            file_put_contents("{$this->target}/Responses/ResponseInterface.php", $contents);
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error generating response',1 , $exception);
        }
    }
}
