<?php

namespace Arquivei\BoltonsScaffolding\Core\Requests;

class Request
{
    private $config;

    public function __construct(string $config)
    {
        $this->config = $config;
    }

    public function getConfig(): string
    {
        return $this->config;
    }
}
