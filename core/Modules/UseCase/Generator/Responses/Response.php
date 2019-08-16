<?php

namespace Arquivei\BoltonsScaffolding\Core\Responses;

use Arquivei\BoltonsScaffolding\Core\Entities\Status;

class Response
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
