<?php

namespace Guzzle\Log;

/**
 * Adapter user that allows Guzzle to log data using various logging implementations
 */
abstract class AbstractLogAdapter implements LogAdapterInterface
{
    protected $log;

    public function getLogObject()
    {
        return $this->log;
    }
}
