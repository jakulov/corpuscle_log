<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 02.01.16
 * Time: 0:30
 */
class EventListenerTest extends PHPUnit_Framework_TestCase
{

    public function testOnShutdown()
    {
        $eventListener = new \jakulov\Corpuscle\Log\EventListener();

        $logger = $this->getMock(\jakulov\Corpuscle\Log\Logger::class, ['flushBuffer']);
        $eventListener->setLogger($logger);

        $logger->expects($this->once())->method('flushBuffer');

        $eventListener->onShutdown();
    }

    public function testOnShutdownIgnore()
    {
        $logger = $this->getMock(\Psr\Log\NullLogger::class);
        $eventListener = new \jakulov\Corpuscle\Log\EventListener();
        $eventListener->setLogger($logger);

        $logger->expects($this->never())->method('flushBuffer');
    }


}
