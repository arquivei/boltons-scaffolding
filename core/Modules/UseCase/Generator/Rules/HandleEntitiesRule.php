<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;


use Arquivei\BoltonsScaffolding\Core\Generics\Utils;
use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;

class HandleEntitiesRule
{
    private $utils;
    private $namespace;
    private $target;
    private $entities;

    public function __construct(string $namespace, string $target, array $entities)
    {
        $this->utils = new Utils();
        $this->namespace = $namespace;
        $this->target = $target;
        $this->entities = $entities;
    }

    public function apply(): void
    {
        try {
            foreach ($this->entities as $entity => $properties) {
                $contents = file_get_contents(__DIR__ . '/../Templates/Entities/Entity.template');
                $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);
                $contents = preg_replace("/\{LESCRIPT_ENTITY_NAME\}/", $entity, $contents);

                $propertyDeclarations = [];
                $getMethods = [];

                foreach ($properties as $name => $type) {
                    $propertyDeclarations[] = $this->utils->getDeclaration($name);
                    $getMethods[] = $this->utils->getGetMethod($name, $type);
                }

                $propertyDeclarations = implode("\n", $propertyDeclarations);
                $contents = preg_replace("/\{LESCRIPT_ENTITY_PROPERTIES\}/", $propertyDeclarations, $contents);

                $getMethods = implode("\n", $getMethods);
                $contents = preg_replace("/\{LESCRIPT_ENTITY_GETTERS\}/", $getMethods, $contents);

                $constructor = $this->utils->getConstructor($properties);
                $contents = preg_replace("/\{LESCRIPT_ENTITY_CONSTRUCTOR\}/", $constructor, $contents);

                file_put_contents("{$this->target}/Entities/{$entity}.php", $contents);
            }

            $contents = file_get_contents(__DIR__ . '/../Templates/Entities/Status.template');
            $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);
            file_put_contents("{$this->target}/Entities/Status.php", $contents);
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error generating entities', 1, $exception);
        }
    }
}
