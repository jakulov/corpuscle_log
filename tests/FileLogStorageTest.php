<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 02.01.16
 * Time: 0:46
 */
class FileLogStorageTest extends PHPUnit_Framework_TestCase
{
    public function testStore()
    {
        $storage = new \jakulov\Corpuscle\Log\FileLogStorage('tests/log', 'test.log');

        $msg = 'test';

        $storage->store($msg);

        $this->assertEquals($msg, trim(file_get_contents('tests/log/test.log')), 'Testing store msg');
    }

    public function testBulk()
    {
        $msg = [
            'test1',
            'test2',
        ];

        $proxy = $this->getMockBuilder(\jakulov\Corpuscle\Log\FileLogStorage::class)
            ->setConstructorArgs(['tests/log', 'test.log'])
            ->setMethods(['store'])
            ->getMock();

        $proxy->expects($this->once())
            ->method('store')->with(join(PHP_EOL, $msg));

        $proxy->bulk($msg);
    }


    protected function setUp()
    {
        if(!is_dir(__DIR__ .'/log')) {
            mkdir(__DIR__ .'/log');
        }
    }

    protected function tearDown()
    {
        if(is_file(__DIR__ .'/log/test.log')) {
            unlink(__DIR__ .'/log/test.log');
        }
        rmdir(__DIR__ .'/log');
    }


}
