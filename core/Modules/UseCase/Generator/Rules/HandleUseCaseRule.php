<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;


use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;
use Arquivei\BoltonsScaffolding\Core\Generics\Utils;

class HandleUseCaseRule
{
    private $namespace;
    private $target;
    private $rules;
    private $utils;
    private $exceptions;

    public function __construct(string $namespace, string $target, array $rules, array $exceptions)
    {
        $this->utils = new Utils();
        $this->rules = $rules;
        $this->namespace = $namespace;
        $this->target = $target;
        $this->exceptions = $exceptions;
    }

    public function apply(): void
    {
        try {
            $contents = file_get_contents(__DIR__ . '/../Templates/UseCase.template');
            $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);

            $withMethodCalls = [];
            $gatewaysToInject = [];
            $importRules = [];
            foreach ($this->rules as $rule => $gateways) {
                $importRules[] = $this->utils->getImport($rule, $this->namespace, 'Rules');
                $withMethodCalls[] = $this->utils->getWithMethodCall($rule, $gateways);
                $gatewaysToInject = array_merge($gatewaysToInject, $gateways);
            }

            if (!empty($importRules)) {
                $importRules = implode("\n", $importRules);
                $contents = preg_replace("/\{LESCRIPT_IMPORT_RULES\}/", $importRules, $contents);
            }

            if (!empty($gatewaysToInject)) {
                $importGateways = [];
                $declareGateways = [];

                foreach ($gatewaysToInject as $name => $type) {
                    $importGateways[] = $this->utils->getImport($type, $this->namespace, 'Gateways');
                    $declareGateways[] = $this->utils->getDeclaration($name);
                }

                $importGateways = implode("\n", $importGateways);
                $contents = preg_replace("/\{LESCRIPT_IMPORT_GATEWAYS\}/", $importGateways, $contents);

                $declareGateways = implode("\n", $declareGateways);
                $contents = preg_replace("/\{LESCRIPT_DECLARE_GATEWAYS\}/", $declareGateways, $contents);

                $gatewaysToInject['logger'] = 'LogInterface';
                $constructor = $this->utils->getConstructor($gatewaysToInject);
                $contents = preg_replace("/\{LESCRIPT_USE_CASE_CONSTRUCTOR\}/", $constructor, $contents);
            }

            if (!empty($withMethodCalls)) {
                $withMethodCalls = implode("\n", $withMethodCalls);
                $contents = preg_replace("/\{LESCRIPT_WITH_CALLS\}/", $withMethodCalls, $contents);
            }

            $catches = [];
            foreach ($this->exceptions as $exception => $catchData) {
                $catches[] = $this->utils->getCatch(
                    $exception,
                    $catchData['logErrorMessage'],
                    $catchData['statusErrorCode'],
                    $catchData['statusErrorMessage']
                );
            }
            $catches = implode("\n", $catches);
            $contents = preg_replace("/\{LESCRIPT_EXCEPTION_CATCHES\}/", $catches, $contents);

            file_put_contents("{$this->target}/UseCase.php", $contents);
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error generating use case', 1, $exception);
        }
    }
}
