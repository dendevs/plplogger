<?php
namespace DenDev\Plplogger\Lib;
use DenDev\Plpadaptability\Adaptability;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\SlackHandler;

use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\ChromePHPFormatter;
use Monolog\Formatter\WildfireFormatter;

use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\WebProcessor;


/**
 * Se charge de la creation des loggers
 *
 * 
 */
class LoggerLib
{
    /** @var array tableau associatif de configuration */
    private $_config;


    /**
     * Ne fait rien, ne sert a rien
     */
    public function __construct( $config )
    {
        $this->_config = $config;
    }

    /**
     * Cree un logger d'Erreur
     *
     * Log sur fichier text rotatif, chrome, FirePHP, et essaye d'envoyer un mail
     *
     * @return object logger
     */
    public function create_error_logger()
    {
        $log_path = $this->_config['log_path'] . 'error_log.html';

        // handler
        $handler_file = new RotatingFileHandler( $log_path, 7, Logger::ERROR );
        $handler_file->setFormatter( new HtmlFormatter() );

        $handler_email = new NativeMailerHandler( 'dendev.mobile@gmail.com', 'test logger', 'ddenis.de.vos@gmail.com', Logger::ALERT );
        $handler_email->setFormatter( new HtmlFormatter() );

        $handler_chrome = new ChromePHPHandler( Logger::ERROR );
        $handler_chrome->setFormatter( new ChromePHPFormatter() );

        $handler_firefox = new FirePHPHandler( Logger::ERROR );
        $handler_firefox->setFormatter( new WildfireFormatter() );

        // logger
        $log = new Logger( 'error' );
        $log->pushProcessor( new GitProcessor() );
        $log->pushProcessor( new IntrospectionProcessor() );

        $log->pushHandler( $handler_firefox);
        $log->pushHandler( $handler_chrome );
        $log->pushHandler( $handler_file );
        $log->pushHandler( $handler_email );


        return $log;
    }

    /**
     * Cree un logger d'Erreur
     *
     * Log sur fichier text rotatif, chrome, FirePHP, et slack
     *
     * @return object logger
     */
   public function create_debug_logger()
    {
        // handler
        $log_path = $this->_config['log_path'] . 'debug_log.html';
        $handler_file = new RotatingFileHandler( $log_path, 7, Logger::ERROR );
        $handler_file->setFormatter( new HtmlFormatter() );

        $handler_chrome = new ChromePHPHandler( Logger::ERROR );
        $handler_chrome->setFormatter( new ChromePHPFormatter() );

        $handler_firefox = new FirePHPHandler( Logger::ERROR );
        $handler_firefox->setFormatter( new WildfireFormatter() );

        $token = $this->_config['slack_token'];
        $channel = $this->_config['slack_channel'];
        $bot_name = $this->_config['slack_bot_name'];
        $bot_img = $this->_config['slack_bot_name'];
        $handler_slack = new SlackHandler( $token , $channel, $bot_name, true, $bot_img, Logger::DEBUG );
       
        // logger
        $log = new Logger( 'debug' );
        $log->pushProcessor( new GitProcessor() );
        $log->pushProcessor( new IntrospectionProcessor() );

        $log->pushHandler( $handler_firefox);
        $log->pushHandler( $handler_chrome );
        $log->pushHandler( $handler_file );
        $log->pushHandler( $handler_slack );


        return $log;
    }

   public function create_perfs_logger()
    {
        // handler
        $log_path = $this->_config['log_path'] . 'perfs_log.html';
        $handler_file = new RotatingFileHandler( $log_path, 7, Logger::INFO );
        $handler_file->setFormatter( new HtmlFormatter() );

        $handler_chrome = new ChromePHPHandler( Logger::INFO );
        $handler_chrome->setFormatter( new ChromePHPFormatter() );

        $handler_firefox = new FirePHPHandler( Logger::INFO );
        $handler_firefox->setFormatter( new WildfireFormatter() );

        // logger
        $log = new Logger( 'perfs' );
        $log->pushProcessor( new IntrospectionProcessor() );
        $log->pushProcessor( new MemoryUsageProcessor() );
        $log->pushProcessor( new MemoryPeakUsageProcessor() );
        $log->pushProcessor( new ProcessIdProcessor() );
        $log->pushProcessor( new WebProcessor() );

        $log->pushHandler( $handler_firefox);
        $log->pushHandler( $handler_chrome );
        $log->pushHandler( $handler_file );


        return $log;
    }

    /**
     * Cree un logger de performances
     *
     * Log sur fichier text rotatif
     *
     * @return object logger
     */
    public function create_custom_logger( $log_name, $default_level = Logger::INFO )
    {
        // handler
        $log_path = $this->_config['log_path'] . $log_name . '_log.html';
        $handler_file = new RotatingFileHandler( $log_path, 7, $default_level );
        $handler_file->setFormatter( new HtmlFormatter() );

        // logger
        $log = new Logger( $log_name );
        $log->pushProcessor( new GitProcessor() );
        $log->pushProcessor( new IntrospectionProcessor() );

        $log->pushHandler( $handler_file );


        return $log;
    }
}
/*
 * refs:
 * http://www.sitepoint.com/logging-with-monolog-from-devtools-to-slack/
 *
 *
 */
