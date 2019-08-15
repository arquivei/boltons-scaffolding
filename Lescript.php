<?php 

require_once('config.php');

class Lescript {
	const LESCRIPT_NAMESPACE = 'LESCRIPT_NAMESPACE';
	const LESCRIPT_IMPORT_RULES = 'LESCRIPT_IMPORT_RULES';
	const LESCRIPT_DECLARE_RULES = 'LESCRIPT_DECLARE_RULES';
	const LESCRIPT_SET_RULES = 'LESCRIPT_SET_RULES';
	const LESCRIPT_APPLY_RULES = 'LESCRIPT_APPLY_RULES';

	const LESCRIPT_ENTITY_NAME = 'LESCRIPT_ENTITY_NAME';
	const LESCRIPT_ENTITY_PROPERTIES = 'LESCRIPT_ENTITY_PROPERTIES';
	const LESCRIPT_ENTITY_CONTRUCTOR = 'LESCRIPT_ENTITY_CONTRUCTOR';
	const LESCRIPT_ENTITY_GETTERS = 'LESCRIPT_ENTITY_GETTERS';

	const LESCRIPT_EXCEPTION_NAME = 'LESCRIPT_EXCEPTION_NAME';

	const LESCRIPT_GATEWAY_NAME = 'LESCRIPT_GATEWAY_NAME';
	const LESCRIPT_GATEWAY_METHODS = 'LESCRIPT_GATEWAY_METHODS';

	const LESCRIPT_REQUEST_PROPERTIES = 'LESCRIPT_REQUEST_PROPERTIES';
	const LESCRIPT_REQUEST_CONSTRUCTOR = 'LESCRIPT_REQUEST_CONSTRUCTOR';
	const LESCRIPT_REQUEST_GETTERS = 'LESCRIPT_REQUEST_GETTERS';

	private function getConstructor($properties = [])
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

	private function getImport(string $name, string $baseNamespace, string $package = '', string $alias = '')
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

	private function getDeclaration(string $className)
	{
		$propertyName = lcfirst($className);
		return "private \${$propertyName};";
	}

	private function getWithMethod(string $className)
	{
		$propertyName = lcfirst($className);

		return "public function with{$className}({$className} \${$propertyName}): Builder
			{
				\$this->{$propertyName} = \${$propertyName};
				return \$this;
			}";
	}

	private function getGetMethod(string $name, string $type) {
		$propertyName = lcfirst($name);
		$className = ucfirst($name);

		return "public function get{$className}(): {$type}
			{
				return \$this->{$propertyName};
			}";
	}

	private function getApplyMethodCall(string $className) 
	{
		$propertyName = lcfirst($className);
		return "\$this->{$propertyName}->apply();";
	}

	private function createFolders()
	{
		global $target;

		mkdir("$target/Builders");
		mkdir("$target/Entities");
		mkdir("$target/Exceptions");
		mkdir("$target/Gateways");
		mkdir("$target/Requests");
		mkdir("$target/Responses");
		mkdir("$target/Responses/Error");
		mkdir("$target/Rules");
	}

	private function handleUseCase()
	{
		global $namespace;
		global $target;

		$contents = file_get_contents('UseCase.php');
		$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);

		file_put_contents("{$target}/UseCase.php", $contents);	
	} 

	private function handleBuilders()
	{
		$contents = file_get_contents('Builders/Builder.php');

		global $namespace;
		global $target;
		global $rules;

		$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);

		$importRules = [];
		$declareRules = [];
		$setRules = [];
		$applyRules = [];

		foreach ($rules as $rule => $gateways) {
			$importRules[] = $this->getImport($rule, $namespace, 'Rules');
			$declareRules[] = $this->getDeclaration($rule);
			$setRules[] = $this->getWithMethod($rule);
			$applyRules[] = $this->getApplyMethodCall($rule);
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

		file_put_contents("{$target}/Builders/Builder.php", $contents);
	}

	private function handleEntities()
	{
		global $namespace;
		global $target;
		global $entities;

		foreach ($entities as $entity => $properties) {
			$contents = file_get_contents('Entities/Entity.php');
			$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);
			$contents = preg_replace("/\{LESCRIPT_ENTITY_NAME\}/", $entity, $contents);			

			$propertyDeclarations = [];
			$getMethods = [];

			foreach ($properties as $name => $type) {
				$propertyDeclarations[] = $this->getDeclaration($name);
				$getMethods[] = $this->getGetMethod($name, $type);
			}

			$propertyDeclarations = implode("\n", $propertyDeclarations);
			$contents = preg_replace("/\{LESCRIPT_ENTITY_PROPERTIES\}/", $propertyDeclarations, $contents);

			$getMethods = implode("\n", $getMethods);
			$contents = preg_replace("/\{LESCRIPT_ENTITY_GETTERS\}/", $getMethods, $contents);			

			$constructor = $this->getConstructor($properties);			
			$contents = preg_replace("/\{LESCRIPT_ENTITY_CONSTRUCTOR\}/", $constructor, $contents);	

			file_put_contents("{$target}/Entities/{$entity}.php", $contents);		
		}

		$contents = file_get_contents('Entities/Status.php');
		$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);
		file_put_contents("{$target}/Entities/Status.php", $contents);		
	}

	private function handleExceptions()
	{
		global $namespace;
		global $target;
		global $exceptions;

		foreach ($exceptions as $exception) {
			$contents = file_get_contents('Exceptions/Exception.php');
			$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);
			$contents = preg_replace("/\{LESCRIPT_EXCEPTION_NAME\}/", $exception, $contents);			

			file_put_contents("{$target}/Exceptions/{$exception}.php", $contents);		
		}
	}

	private function handleGateways()
	{
		global $namespace;
		global $target;
		global $gateways;

		foreach ($gateways as $gateway) {
			$contents = file_get_contents('Gateways/Gateway.php');
			$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);
			$contents = preg_replace("/\{LESCRIPT_GATEWAY_NAME\}/", $gateway, $contents);			

			file_put_contents("{$target}/Gateways/{$gateway}.php", $contents);		
		}
	}

	private function handleRequests()
	{
		global $namespace;
		global $target;
		global $requestProperties;

		$contents = file_get_contents('Requests/Request.php');
		$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);

		$propertyDeclarations = [];
		$getMethods = [];

		foreach ($requestProperties as $name => $type) {
			$propertyDeclarations[] = $this->getDeclaration($name);
			$getMethods[] = $this->getGetMethod($name, $type);
		}

		$propertyDeclarations = implode("\n", $propertyDeclarations);
		$contents = preg_replace("/\{LESCRIPT_REQUEST_PROPERTIES\}/", $propertyDeclarations, $contents);

		$getMethods = implode("\n", $getMethods);
		$contents = preg_replace("/\{LESCRIPT_REQUEST_GETTERS\}/", $getMethods, $contents);			

		$constructor = $this->getConstructor($requestProperties);			
		$contents = preg_replace("/\{LESCRIPT_REQUEST_CONSTRUCTOR\}/", $constructor, $contents);	

		file_put_contents("{$target}/Requests/Request.php", $contents);		
	}

	private function handleResponse()
	{
		global $namespace;
		global $target;

		$contents = file_get_contents('Responses/Response.php');
		$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);

		file_put_contents("{$target}/Responses/Response.php", $contents);			
	}

	private function handleErrorResponse()
	{
		global $namespace;
		global $target;

		$contents = file_get_contents('Responses/Error/Response.php');
		$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);

		file_put_contents("{$target}/Responses/Error/Response.php", $contents);	
	}

	private function handleResponseInterface()
	{
		global $namespace;
		global $target;

		$contents = file_get_contents('Responses/ResponseInterface.php');
		$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);

		file_put_contents("{$target}/Responses/ResponseInterface.php", $contents);	
	}

	private function handleResponses()
	{
		$this->handleResponse();
		$this->handleErrorResponse();
		$this->handleResponseInterface();
	}

	private function handleRules()
	{
		global $target;
		global $namespace;
		global $rules;

		foreach ($rules as $rule => $gateways) {
			$contents = file_get_contents('Rules/Rule.php');

			$contents = preg_replace("/\{LESCRIPT_NAMESPACE\}/", $namespace, $contents);
	
			$imports = [];
			$ruleProperties = [];

			foreach ($gateways as $name => $type) {
				$imports[] = $this->getImport($type, $namespace, 'Gateways');
				$ruleProperties[] = $this->getDeclaration($type);
			}

			$imports = implode("\n", $imports);
			$contents = preg_replace("/\{LESCRIPT_RULE_IMPORTS\}/", $imports, $contents);

			$ruleProperties = implode("\n", $ruleProperties);
			$contents = preg_replace("/\{LESCRIPT_RULE_PROPERTIES\}/", $ruleProperties, $contents);

			$contents = preg_replace("/\{LESCRIPT_RULE_NAME\}/", $rule, $contents);

			$constructor = $this->getConstructor($gateways);
			$contents = preg_replace("/\{LESCRIPT_RULE_CONSTRUCTOR\}/", $constructor, $contents);

			file_put_contents("{$target}/Rules/{$rule}.php", $contents);	
		}
	}

	public function makeLeMagique()
	{
		$this->createFolders();
		$this->handleUseCase();
		$this->handleBuilders();
		$this->handleEntities();
		$this->handleExceptions();
		$this->handleGateways();
		$this->handleRequests();
		$this->handleResponses();
		$this->handleRules();
	}
}

$leScript = new Lescript();
$leScript->makeLeMagique();
