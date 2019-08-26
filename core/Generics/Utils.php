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

    public function getWithMethodCall(string $rule, array $gateways)
    {
        $gatewayProperties = array_keys($gateways);
        foreach ($gatewayProperties as &$property) {
            $property = "\$this->{$property}";
        }
        unset($property);

        $gatewayProperties = implode(', ', $gatewayProperties);

        return "->with{$rule}(new {$rule}({$gatewayProperties}))";
    }

    public function getCatch(
        string $exception,
        string $logErrorMessage = 'Error',
        string $statusErrorCode = 'ERROR_UNKNOWN',
        string $statusErrorMessage = 'Unknown error'
    ) {
        $exceptionName = lcfirst($exception);

        return "catch ({$exception} \${$exceptionName}) {
            \$this->logger->error('{$logErrorMessage}', ['exception' => \${$exceptionName}]);
            \$this->response = new ErrorResponse(new Status(Status::{$statusErrorCode}, '${statusErrorMessage}'));
        }";
    }

    public function getConstDeclaration(string $const, string $value)
    {
        return "const {$const} = '{$value}';";
    }
}
