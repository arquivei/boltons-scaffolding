<?php

namespace Arquivei\BoltonsScaffolding\Core\Entities;

class Config
{
    private $propertyName;

    public function __construct(propertyType $propertyName)
    {
        $this->propertyName = $propertyName;
    }

    public function getPropertyName(): propertyType
    {
        return $this->propertyName;
    }
}
