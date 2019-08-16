<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;

use Arquivei\BoltonsScaffolding\Core\Generics\Utils;
use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;

class HandleRequestsRule
{
    private $utils;
    private $namespace;
    private $target;
    private $requestProperties;

    public function __construct(string $namespace, string $target, array $requestProperties)
    {
        $this->utils = new Utils();
        $this->namespace = $namespace;
        $this->target = $target;
        $this->requestProperties = $requestProperties;
    }

    public function apply(): void
    {
        try {
            $contents = file_get_contents(__DIR__ . '/../Templates/Requests/Request.template');
            $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);

            $propertyDeclarations = [];
            $getMethods = [];

            foreach ($this->requestProperties as $name => $type) {
                $propertyDeclarations[] = $this->utils->getDeclaration($name);
                $getMethods[] = $this->utils->getGetMethod($name, $type);
            }

            $propertyDeclarations = implode("\n", $propertyDeclarations);
            $contents = preg_replace("/\{LESCRIPT_REQUEST_PROPERTIES\}/", $propertyDeclarations, $contents);

            $getMethods = implode("\n", $getMethods);
            $contents = preg_replace("/\{LESCRIPT_REQUEST_GETTERS\}/", $getMethods, $contents);

            $constructor = $this->utils->getConstructor($this->requestProperties);
            $contents = preg_replace("/\{LESCRIPT_REQUEST_CONSTRUCTOR\}/", $constructor, $contents);

            file_put_contents("{$this->target}/Requests/Request.php", $contents);
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error generating request', 1, $exception);
        }
    }
}
