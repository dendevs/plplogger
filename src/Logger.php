<?php
namespace DenDev\Plplogger;
use DenDev\Plplogger\LoggerInterface;
use DenDev\Plpadaptability\Adaptability;
use DenDev\Plplogger\Lib\LoggerLib;


/**
 * Met en place les loggers et les moyens d'y ecrire 
 */
class Logger extends Adaptability implements LoggerInterface
{
    /** @var array stock les instances des loggers */
    private $_logger_lib;


    /**
     * Set le kernel du servie 
     *
     * @param object $krl la ref du kernel auquel appartient le service ou false par defaut.
     * @param array $args tableau associatif de configuration
     *
     * @return void
     */
    public function __construct( $krl = false, $args = array() )
    {
        parent::__construct( $krl );
        $this->_logger_lib = new LoggerLib( $this->get_config_values() );
        $this->_set_default_loggers();
    }

    /**
     * Configuration par defaut du service
     *
     * @return array tableau associatif option value.
     */
    public function get_default_configs()
    {
	return array(
	    'log_path' => sys_get_temp_dir() . '/logs/',
	    'slack_token' => '',
	    'slack_channel' => '#plplog',
	    'slack_bot_name' => 'pulpy',
	    'slack_bot_img' => ':octopus:',
	);
    }

    /**
     * Set les informations de base au sujet du service.
     *
     * son nom sous forme slugifier ( mon_serice et non Mon service )
     * son numero de version 
     *
     * @return void
     */
    public function set_service_metas()
    {
	$this->_service_metas = array( 
	    'service_name' => 'logger',
	    'service_version' => '0.0.0',
	);
    }

    public function add_file_logger( $log_name, $default_level = \Monolog\Logger::INFO )
    {
	$logger = false;
	if( ! array_key_exists( $log_name, $this->_loggers ) )
	{
	    $logger = $this->_logger_lib->create_custom_logger( $log_name, $default_level );
	    $this->_loggers[$log_name] = $logger;
	}
	return $logger;
    }

    public function get_logger( $log_name )
    {
	$logger = false;
	if( array_key_exists( $log_name, $this->_loggers ) )
	{
	    $logger = $this->_loggers[$log_name];
	}
	else
	{
	    $logger = $this->add_file_logger( $log_name );
	}
	return $logger;
    }

    public function delete_logger( $log_name )
    {
	$remove = false;
	if( array_key_exists( $log_name, $this->_loggers ) )
	{
	    unset( $this->_loggers[$log_name] );
	    $remove = true;
	}
	return $remove;
    }

    // write log
    public function log( $log_name, $message, $level = 'info', $extras = array() )
    {
	$ok = false;
	$levels_methode = array( 'debug' => 'addDebug', 
	    'info' => 'addInfo',
	    'notice' => 'addNotice',
	    'warning' => 'addWarning',
	    'error' => 'addError',
	    'critical' => 'addCritical',
	    'alert' => 'addAlert',
	    'emergency' => 'addEmergency'
	);
	$logger = $this->get_logger( $log_name );
	$level = strtolower( $level );
	if( $logger && array_key_exists( $level, $levels_methode ) )
	{
	    $name_method_add_message = $levels_methode[$level];
	    $ok = $logger->$name_method_add_message( $message, $extras );
	}
	return $ok;
    }

    // -
    private function _set_default_loggers()
    {
	$this->_loggers = array();
	// error
	$this->_loggers['error'] = $this->_logger_lib->create_error_logger();
	// debug
	$this->_loggers['debug'] = $this->_logger_lib->create_debug_logger();
	// perfs
	$this->_loggers['perfs'] = $this->_logger_lib->create_perfs_logger();
    }
}
