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

	public function test_get_default_logger()
	{
		$object = new Logger();
		$this->assertInstanceOf( 'Monolog\Logger', $object->get_logger( 'error' ) ); 
		$this->assertInstanceOf( 'Monolog\Logger', $object->get_logger( 'debug' ) ); 
		$this->assertInstanceOf( 'Monolog\Logger', $object->get_logger( 'perfs' ) ); 
	}
}
