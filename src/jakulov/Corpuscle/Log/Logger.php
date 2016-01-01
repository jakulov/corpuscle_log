<?php
namespace jakulov\Corpuscle\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Logger
 * @package jakulov\Corpuscle\Log
 */
class Logger extends AbstractLogger
{
    /** @var Request */
    protected $request;
    /** @var LogStorageInterface */
    protected $storage;
    /** @var string */
    protected $level = LogLevel::DEBUG;
    /** @var int */
    protected $levelValue = 0;
    /** @var bool */
    protected $useBuffer = false;
    /** @var array */
    protected $buffer = [];
    /** @var array */
    protected $logHierarchy = [
        LogLevel::DEBUG => 0,
        LogLevel::INFO => 1,
        LogLevel::NOTICE => 2,
        LogLevel::WARNING => 3,
        LogLevel::ERROR => 4,
        LogLevel::CRITICAL => 5,
        LogLevel::ALERT => 6,
        LogLevel::EMERGENCY => 7,
    ];

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        if(isset($config['buffer'])) {
            $this->useBuffer = (bool)$config['buffer'];
        }
        if(isset($config['level'])) {
            $this->level = (string)$config['level'];
        }
        $this->levelValue = $this->logHierarchy[$this->level];
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return $this
     */
    public function flushBuffer()
    {
        $this->storage->bulk($this->buffer);
        $this->buffer = [];

        return $this;
    }

    /**
     * @param LogStorageInterface $logStorageInterface
     */
    public function setLogStorage(LogStorageInterface $logStorageInterface)
    {
        $this->storage = $logStorageInterface;
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        if($this->levelValue <= $this->logHierarchy[$level]) {
            if($this->useBuffer) {
                $this->buffer[] = $this->composeLogMsg($level, $message, $context);
            }
            else {
                $this->storage->store($this->composeLogMsg($level, $message, $context));
            }
        }

        return null;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function composeLogMsg($level, $message, array $context = [])
    {
        foreach($context as $key => $value) {
            $message = str_replace(
                '{'. $key .'}', $value instanceof \Exception ? $value->getMessage() : $value, $message
            );
        }

        return join("\t", [ date('c'), $this->request->getClientIp(), $level, $message ]);
    }
}