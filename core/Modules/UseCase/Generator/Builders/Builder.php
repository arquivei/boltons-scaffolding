<?php

namespace Arquivei\BoltonsScaffolding\Core\Builders;

use Arquivei\BoltonsScaffolding\Core\Entities\Status;
use Arquivei\BoltonsScaffolding\Core\Responses\Response;
use Arquivei\BoltonsScaffolding\Core\Rules\CreateFoldersRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleUseCaseRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleBuildersRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleEntitiesRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleExceptionsRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleGatewaysRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleRequestsRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleResponsesRule;
use Arquivei\BoltonsScaffolding\Core\Rules\HandleRulesRule;

class Builder
{
    private $createFoldersRule;
    private $handleUseCaseRule;
    private $handleBuildersRule;
    private $handleEntitiesRule;
    private $handleExceptionsRule;
    private $handleGatewaysRule;
    private $handleRequestsRule;
    private $handleResponsesRule;
    private $handleRulesRule;

    public function withCreateFoldersRule(CreateFoldersRule $createFoldersRule): Builder
    {
        $this->createFoldersRule = $createFoldersRule;
        return $this;
    }

    public function withHandleUseCaseRule(HandleUseCaseRule $handleUseCaseRule): Builder
    {
        $this->handleUseCaseRule = $handleUseCaseRule;
        return $this;
    }

    public function withHandleBuildersRule(HandleBuildersRule $handleBuildersRule): Builder
    {
        $this->handleBuildersRule = $handleBuildersRule;
        return $this;
    }

    public function withHandleEntitiesRule(HandleEntitiesRule $handleEntitiesRule): Builder
    {
        $this->handleEntitiesRule = $handleEntitiesRule;
        return $this;
    }

    public function withHandleExceptionsRule(HandleExceptionsRule $handleExceptionsRule): Builder
    {
        $this->handleExceptionsRule = $handleExceptionsRule;
        return $this;
    }

    public function withHandleGatewaysRule(HandleGatewaysRule $handleGatewaysRule): Builder
    {
        $this->handleGatewaysRule = $handleGatewaysRule;
        return $this;
    }

    public function withHandleRequestsRule(HandleRequestsRule $handleRequestsRule): Builder
    {
        $this->handleRequestsRule = $handleRequestsRule;
        return $this;
    }

    public function withHandleResponsesRule(HandleResponsesRule $handleResponsesRule): Builder
    {
        $this->handleResponsesRule = $handleResponsesRule;
        return $this;
    }

    public function withHandleRulesRule(HandleRulesRule $handleRulesRule): Builder
    {
        $this->handleRulesRule = $handleRulesRule;
        return $this;
    }

    public function build(): Response
    {
        $this->createFoldersRule->apply();
        $this->handleUseCaseRule->apply();
        $this->handleBuildersRule->apply();
        $this->handleEntitiesRule->apply();
        $this->handleExceptionsRule->apply();
        $this->handleGatewaysRule->apply();
        $this->handleRequestsRule->apply();
        $this->handleResponsesRule->apply();
        $this->handleRulesRule->apply();

        return new Response(new Status('OK', 'OK'));
    }
}
