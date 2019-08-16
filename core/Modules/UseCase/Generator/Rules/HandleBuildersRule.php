<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;


use Arquivei\BoltonsScaffolding\Core\Generics\Utils;
use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;

class HandleBuildersRule
{
    private $utils;
    private $namespace;
    private $target;
    private $rules;


    public function __construct(string $namespace, string $target, array $rules)
    {
        $this->utils = new Utils();
        $this->namespace = $namespace;
        $this->target = $target;
        $this->rules = $rules;
    }

    public function apply(): void
    {
        try {
            $contents = file_get_contents(__DIR__ . '/../Templates/Builders/Builder.template');

            $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);

            $importRules = [];
            $declareRules = [];
            $setRules = [];
            $applyRules = [];

            foreach ($this->rules as $rule => $gateways) {
                $importRules[] = $this->utils->getImport($rule, $this->namespace, 'Rules');
                $declareRules[] = $this->utils->getDeclaration($rule);
                $setRules[] = $this->utils->getWithMethod($rule);
                $applyRules[] = $this->utils->getApplyMethodCall($rule);
            }

            if (!empty($importRules)) {
                $importRules = implode("\n", $importRules);
                $contents = preg_replace("/\{LESCRIPT_IMPORT_RULES\}/", $importRules, $contents);
            }

            if (!empty($declareRules)) {
                $declareRules = implode("\n", $declareRules);
                $contents = preg_replace("/\{LESCRIPT_DECLARE_RULES\}/", $declareRules, $contents);
            }

            if (!empty($setRules)) {
                $setRules = implode("\n", $setRules);
                $contents = preg_replace("/\{LESCRIPT_SET_RULES\}/", $setRules, $contents);
            }

            if (!empty($applyRules)) {
                $applyRules = implode("\n", $applyRules);
                $contents = preg_replace("/\{LESCRIPT_APPLY_RULES\}/", $applyRules, $contents);
            }

            file_put_contents("{$this->target}/Builders/Builder.php", $contents);
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error creating builders', 1, $exception);
        }
    }
}
