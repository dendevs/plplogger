<?php
namespace DenDev\Plplogger\Test;
use DenDev\Plplogger\Logger;


class LoggerTest extends \PHPUnit_Framework_TestCase 
{
	public function test_instanciate()
	{
		$object = new Logger();
		$this->assertInstanceOf( "DenDev\Plplogger\Logger", $object );
	}
}
