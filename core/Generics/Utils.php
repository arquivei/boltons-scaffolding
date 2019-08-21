<?php

namespace Arquivei\BoltonsScaffolding\Core\Generics;

class Utils
{
    public function getConstructor($properties = [])
    {
        $signature = [];
        $assignments = [];

        foreach ($properties as $name => $type) {
            $signature[] = "{$type} \${$name}";
            $assignments[] = "\$this->{$name} = \${$name};";
        }

        $signature = implode(', ', $signature);
        $assignments = implode("\n", $assignments);

        return "public function __construct({$signature})
		{
			{$assignments}
		}";
    }

    public function getImport(string $name, string $baseNamespace, string $package = '', string $alias = '')
    {
        $import = "use {$baseNamespace}";

        if (!empty($package)) {
            $import .= "\\{$package}";
        }

        $import .= "\\{$name}";

        if (!empty($alias)) {
            $import .= " as {$alias}";
        }

        $import .= ";";

        return $import;
    }

    public function getDeclaration(string $className)
    {
        $propertyName = lcfirst($className);
        return "private \${$propertyName};";
    }

    public function getWithMethod(string $className)
    {
        $propertyName = lcfirst($className);

        return "public function with{$className}({$className} \${$propertyName}): Builder
			{
				\$this->{$propertyName} = \${$propertyName};
				return \$this;
			}";
    }

    public function getGetMethod(string $name, string $type) {
        $propertyName = lcfirst($name);
        $className = ucfirst($name);

        return "public function get{$className}(): {$type}
			{
				return \$this->{$propertyName};
			}";
    }

    public function getApplyMethodCall(string $className)
    {
        $propertyName = lcfirst($className);
        return "\$this->{$propertyName}->apply();";
    }
}
