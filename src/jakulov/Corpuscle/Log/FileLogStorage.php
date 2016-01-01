<?php
namespace jakulov\Corpuscle\Log;

/**
 * Class FileLogStorage
 * @package jakulov\Corpuscle\Log
 */
class FileLogStorage implements LogStorageInterface
{
    /** @var string */
    protected $dir;
    /** @var string */
    protected $file;
    /** @var string */
    protected $filePath;

    /**
     * FileLogStorage constructor.
     * @param $dir
     * @param $file
     */
    public function __construct($dir, $file)
    {
        $this->dir = $dir;
        $this->file = $file;
        if(!is_dir($this->dir)) {
            if(mkdir($this->dir, 0777, true) === false) {
                throw new \InvalidArgumentException(sprintf('Unable to create log dir %', $this->dir));
            }
        }
        if(!$this->file) {
            throw new \BadMethodCallException('Argument file should not be empty');
        }

        $this->filePath = $this->dir .'/'. $this->file;
    }

    /**
     * @param string $message
     * @return bool
     */
    public function store($message)
    {
        if(!is_dir(dirname($this->filePath))) {
            if(mkdir(dirname($this->filePath), 0777, true) === false) {
                throw new \RuntimeException(sprintf('Unable to create log directory: %s', dirname($this->filePath)));
            }
        }

        return (bool)file_put_contents($this->filePath, $message . PHP_EOL, FILE_APPEND);
    }

    /**
     * @param array $messages
     * @return bool
     */
    public function bulk(array $messages)
    {
        return $this->store(join(PHP_EOL, $messages));
    }

}