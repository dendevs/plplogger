<?php
use DenDev\Plplogger\Lib\FileLib;


class FileLibTest extends PHPUnit_Framework_TestCase 
{
	private $_path;


	public function setUp()
	{
		$this->_path = sys_get_temp_dir() . '/';
	}

	public function test_instanciate()
	{
		$object = new FileLib( $this->_path, 'test', 'txt' );
		$this->assertInstanceOf( "DenDev\Plplogger\Lib\FileLib", $object );
	}

	public function test_instanciate_args()
	{
		$refs = $this->_path . 'test.txt';

		// defaults
		$object = new FileLib( sys_get_temp_dir(), 'test', 'txt' );
		$this->assertEquals( $refs, $object->get_full_path() );

		// with .ext
		$object = new FileLib( sys_get_temp_dir(), 'test', '.txt' );
		$this->assertEquals( $refs, $object->get_full_path() );

		// with .ext
		$object = new FileLib( sys_get_temp_dir(), 'test', '.txt' );
		$this->assertEquals( $refs, $object->get_full_path() );

		// with / and .ext
		$object = new FileLib( sys_get_temp_dir(), '/test', '.txt' );
		$this->assertEquals( $refs, $object->get_full_path() );
	}

	public function test_is_valide()
	{
		file_put_contents( $this->_path . 'file_exists.txt', 'test file exist' );

		$object = new FileLib( $this->_path, 'file_exists', 'txt' );
		$this->assertTrue( $object->is_valide() );
	}

	public function test_create_file()
	{
		$object = new FileLib( $this->_path, 'test', 'txt' );
		$this->assertTrue( $object->create_file() );
		$this->assertTrue( file_exists( $this->_path . 'test.txt' ) );
	}

	public function tearDown()
	{
		if( file_exists( $this->_path . 'test.txt' ) )
		{
			unlink( $this->_path . 'test.txt' );
		}

		if( file_exists( $this->_path . 'file_exists.txt' ) )
		{
			unlink( $this->_path . 'file_exists.txt' );
		}
	}
}

