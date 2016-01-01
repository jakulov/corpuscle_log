<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 02.01.16
 * Time: 1:21
 */
class LoggerTest extends PHPUnit_Framework_TestCase
{
    /** @var  \Symfony\Component\HttpFoundation\Request */
    protected $request;
    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $storage;

    protected function getLogger($config = [])
    {
        $this->storage = $this->getMock(\jakulov\Corpuscle\Log\FileLogStorage::class, ['store'], ['tests', 'tests.log']);

        $config = $config ? $config : [
            'buffer' => false,
            'level' => \Psr\Log\LogLevel::DEBUG,
        ];

        $this->request = \Symfony\Component\HttpFoundation\Request::create('/tests');
        $logger = new \jakulov\Corpuscle\Log\Logger();
        $logger->setConfig($config);
        $logger->setLogStorage($this->storage);
        $logger->setRequest($this->request);

        return $logger;
    }


    public function testLog()
    {
        $logger = $this->getLogger();

        $message = 'TEst MeSSage';
        $level = \Psr\Log\LogLevel::DEBUG;
        $fullMsg = join("\t", [ date('c'), $this->request->getClientIp(), $level, $message ]);

        $this->storage->expects($this->once())->method('store')->with($fullMsg);
        $logger->log($level, $message, []);
    }

    public function testContext()
    {
        $logger = $this->getLogger();

        $message = '{test} with {context} and {exception}';

        $level = \Psr\Log\LogLevel::DEBUG;
        $e = new Exception('Dummy');
        $context = [
            'test' => 'Foo',
            'context' => 'Bar',
            'exception' => $e,
        ];

        $fullMsg = $context['test'] .' with '. $context['context'] .' and '. $e->getMessage();
        $fullMsg = join("\t", [ date('c'), $this->request->getClientIp(), $level, $fullMsg ]);

        $this->storage->expects($this->once())->method('store')->with($fullMsg);
        $logger->log($level, $message, $context);
    }

    public function testLevel()
    {
        $logger = $this->getLogger([
            'buffer' => false,
            'level' => \Psr\Log\LogLevel::INFO,
        ]);

        $message = 'TEst MeSSage';
        $level = \Psr\Log\LogLevel::INFO;

        $message2 = 'TEst2 MeSSage2';
        $level2 = \Psr\Log\LogLevel::DEBUG;

        $fullMsg = join("\t", [ date('c'), $this->request->getClientIp(), $level, $message ]);

        $this->storage->expects($this->once())->method('store')->with($fullMsg);

        $logger->log($level, $message);
        $logger->log($level2, $message2);
    }

    public function testBuffer()
    {
        $logger = $this->getLogger([
            'buffer' => true,
            'level' => \Psr\Log\LogLevel::ERROR,
        ]);

        $message = 'TEst MeSSage';
        $level = \Psr\Log\LogLevel::EMERGENCY;

        $message2 = 'TEst2 MeSSage2';
        $level2 = \Psr\Log\LogLevel::CRITICAL;

        $fullMsg = join(PHP_EOL, [
            join("\t", [ date('c'), $this->request->getClientIp(), $level, $message ]),
            join("\t", [ date('c'), $this->request->getClientIp(), $level2, $message2 ]),
        ]);

        $this->storage->expects($this->once())->method('store')->with($fullMsg);

        $logger->emergency($message);
        $logger->critical($message2);

        $logger->flushBuffer();
    }


}
