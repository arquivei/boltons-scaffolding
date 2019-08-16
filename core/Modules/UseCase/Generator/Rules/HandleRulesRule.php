<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;

use Arquivei\BoltonsScaffolding\Core\Generics\Utils;
use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;

class HandleRulesRule
{
    private $target;
    private $namespace;
    private $rules;
    private $utils;

    public function __construct(string $namespace, string $target, array $rules)
    {
        $this->utils = new Utils();
        $this->target = $target;
        $this->namespace = $namespace;
        $this->rules = $rules;
    }

    public function apply(): void
    {
        try {
            foreach ($this->rules as $rule => $gateways) {
                $contents = file_get_contents(__DIR__ . '/../Templates/Rules/Rule.template');

                $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);

                $imports = [];
                $ruleProperties = [];

                foreach ($gateways as $name => $type) {
                    $imports[] = $this->utils->getImport($type, $this->namespace, 'Gateways');
                    $ruleProperties[] = $this->utils->getDeclaration($type);
                }

                $imports = implode("\n", $imports);
                $contents = preg_replace("/\{LESCRIPT_RULE_IMPORTS\}/", $imports, $contents);

                $ruleProperties = implode("\n", $ruleProperties);
                $contents = preg_replace("/\{LESCRIPT_RULE_PROPERTIES\}/", $ruleProperties, $contents);

                $contents = preg_replace("/\{LESCRIPT_RULE_NAME\}/", $rule, $contents);

                $constructor = $this->utils->getConstructor($gateways);
                $contents = preg_replace("/\{LESCRIPT_RULE_CONSTRUCTOR\}/", $constructor, $contents);

                file_put_contents("{$this->target}/Rules/{$rule}.php", $contents);
            }
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error generating rules', 1, $exception);
        }
    }
}
