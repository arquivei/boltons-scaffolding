<?php

namespace {LESCRIPT_NAMESPACE}\Responses;

use {LESCRIPT_NAMESPACE}\Entities\Status;

class Response implements ResponseInterface
{
    private $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
