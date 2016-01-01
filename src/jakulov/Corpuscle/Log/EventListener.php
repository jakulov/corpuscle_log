<?php
namespace jakulov\Corpuscle\Log;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class EventListener
 * @package jakulov\Corpuscle\Log
 */
class EventListener implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * flush logs on app shutdown
     */
    public function onShutdown()
    {
        if ($this->logger instanceof Logger) {
            $this->logger->flushBuffer();
        }
    }
}