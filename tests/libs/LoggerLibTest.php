<?php
use DenDev\Plplogger\Lib\LoggerLib;


class LoggerLibTest extends PHPUnit_Framework_TestCase 
{
	private $_path;


	public function setUp()
	{
		$this->_configs = array( 'log_path' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test/' );
	}

	public function test_instanciate()
	{
		$object = new LoggerLib( $this->_configs );
		$this->assertInstanceOf( "DenDev\Plplogger\Lib\LoggerLib", $object );
	}

	public function test_create_log()
	{
		$object = new LoggerLib( $this->_configs );
		$this->assertInstanceOf( 'Monolog\Logger', $object->create_error_logger() );
	}

	public function test_write_log()
	{
		$object = new LoggerLib( $this->_configs );
		$logger = $object->create_error_logger();
		$this->assertEquals( false, $logger->addWarning( 'jkljkl' ) );
		$this->assertEquals( 1, $logger->addError( 'jkljkl' ) );
	}

	public function test_create_custom_logger()
	{
		$object = new LoggerLib( $this->_configs );
		$logger = $object->create_custom_logger( 'custom' );
		$this->assertInstanceOf( 'Monolog\Logger', $logger );
		$this->assertEquals( false, $logger->addDebug( 'debug message', array( 'test' => 'ok' ) ) );
		$this->assertEquals( 1, $logger->addWarning( 'warning message', array( 'test' => 'ok' ) ) );
	}

	public function tearDown()
	{
		if( file_exists( $this->_configs['log_path'] ) && is_dir( $this->_configs['log_path'] ) )
		{
			foreach( scandir( $this->_configs['log_path'] ) as $tmp )
			{
				if( is_file( $this->_configs['log_path'] . $tmp ) )
				{
					unlink( $this->_configs['log_path'] . $tmp );
				}
			}
			rmdir( $this->_configs['log_path'] );
		}
	}
}

