<?php

namespace Arquivei\BoltonsScaffolding\Core;

use Arquivei\BoltonsScaffolding\Core\Builders\Builder;
use Arquivei\BoltonsScaffolding\Core\Entities\Status;
use Arquivei\BoltonsScaffolding\Core\Requests\Request;
use Arquivei\BoltonsScaffolding\Core\Responses\Response;
use Arquivei\BoltonsScaffolding\Core\Responses\ResponseInterface;
use Arquivei\BoltonsScaffolding\Core\Responses\Error\Response as ErrorResponse;
use Arquivei\BoltonsScaffolding\Core\Rules\CreateFoldersRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleBuildersRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleEntitiesRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleExceptionsRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleGatewaysRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleRequestsRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleResponsesRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleRulesRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleUseCaseRule;

class UseCase
{
    public function execute(Request $request): Response
    {
        $config = json_decode(file_get_contents($request->getConfig()), true);

        $target = $config['target'];
        $namespace = $config['namespace'];
        $entities = $config['entities'];
        $exceptions = $config['exceptions'];
        $gateways = $config['gateways'];
        $requestProperties = $config['requestProperties'];
        $rules = $config['rules'];


        return (new Builder())
            ->withCreateFoldersRule(new CreateFoldersRule($target))
            ->withHandleBuildersRule(new HandleBuildersRule($namespace, $target, $rules))
            ->withHandleEntitiesRule(new HandleEntitiesRule($namespace, $target, $entities))
            ->withHandleExceptionsRule(new HandleExceptionsRule($namespace, $target, $exceptions))
            ->withHandleGatewaysRule(new HandleGatewaysRule($namespace, $target, $gateways))
            ->withHandleRequestsRule(new HandleRequestsRule($namespace, $target, $requestProperties))
            ->withHandleResponsesRule(new HandleResponsesRule($namespace, $target))
            ->withHandleRulesRule(new HandleRulesRule($namespace, $target, $rules))
            ->withHandleUseCaseRule(new HandleUseCaseRule($namespace, $target))
            ->build();
    }
}
