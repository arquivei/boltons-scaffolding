<?php

namespace Arquivei\BoltonsScaffolding\Core\Rules;

use Arquivei\BoltonsScaffolding\Core\Exceptions\UseCaseGenerationException;

class HandleGatewaysRule
{
    private $namespace;
    private $target;
    private $gateways;

    public function __construct(string $namespace, string $target, array $gateways)
    {
        $this->namespace = $namespace;
        $this->target = $target;
        $this->gateways = $gateways;
    }

    public function apply(): void
    {
        try {
            foreach ($this->gateways as $gateway) {
                $contents = file_get_contents(__DIR__ . '/../Templates/Gateways/Gateway.template');
                $contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $this->namespace, $contents);
                $contents = preg_replace("/\{LESCRIPT_GATEWAY_NAME\}/", $gateway, $contents);

                file_put_contents("{$this->target}/Gateways/{$gateway}.php", $contents);
            }
        } catch (\Throwable $exception) {
            throw new UseCaseGenerationException('Error generating gateways',1 , $exception);
        }
    }
}
