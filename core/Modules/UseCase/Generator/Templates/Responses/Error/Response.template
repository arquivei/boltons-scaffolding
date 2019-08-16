<?php

namespace {LESCRIPT_NAMESPACE}\Responses\Error;

use {LESCRIPT_NAMESPACE}\Entities\Status;
use {LESCRIPT_NAMESPACE}\Responses\ResponseInterface;

class Response implements ResponseInterface
{
    private $error;
    private $status;

    public function __construct(Status $status, string $error)
    {
        $this->status = $status;
        $this->error = $error;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
