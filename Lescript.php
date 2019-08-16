<?php

namespace Arquivei\BoltonsScaffolding;

use Arquivei\BoltonsScaffolding\Core\Requests\Request;
use Arquivei\BoltonsScaffolding\Core\UseCase;

class Lescript {
    private $configPath;

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
    }

	public function makeLeMagique()
	{
        $request = new Request($this->configPath);

        $useCase = new UseCase();
        return $useCase->execute($request);
	}
}
