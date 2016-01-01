<?php
namespace jakulov\Corpuscle\Log;

/**
 * Interface LogStorageInterface
 * @package jakulov\Corpuscle\Log
 */
interface LogStorageInterface
{
    /**
     * @param string $message
     * @return bool
     */
    public function store($message);

    /**
     * @param array $messages
     * @return bool
     */
    public function bulk(array $messages);
}