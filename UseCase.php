<?php

namespace {LESCRIPT_NAMESPACE};

use {LESCRIPT_NAMESPACE}\Builders\Builder;
use {LESCRIPT_NAMESPACE}\Entities\Status;
use {LESCRIPT_NAMESPACE}\Requests\Request;
use {LESCRIPT_NAMESPACE}\Responses\ResponseInterface;
use {LESCRIPT_NAMESPACE}\Responses\Error\Response as ErrorResponse;

class UseCase
{
    private $response;
    private $logger;

    public function __construct(
        LogInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function execute(Request $request)
    {
        try {
            $this->response = (new Builder())
                ->build();
        } catch (\Throwable $exception) {
            $this->logger->error(
                "Error",
                [
                    'exception' => $exception
                ]
            );
            $this->response = new ErrorResponse(new Status(500, "Unknown error"), "Unknown error");
        }
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
