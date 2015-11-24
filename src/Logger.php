<?php
namespace DenDev\Plplogger;
use DenDev\Plplogger\LoggerInterface;
use DenDev\Plpadaptability\Adaptability;


/**
 * Met en place les loggers et les moyens d'y ecrire 
 */
class Logger extends Adaptability implements LoggerInterface
{
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
        $this->_set_default_loggers();
    }

    /**
     * Configuration par defaut du service
     *
     * @return array tableau associatif option value.
     */
    public function get_default_configs()
    {
        return array();
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
