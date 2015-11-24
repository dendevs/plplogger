<?php
namespace DenDev\Plplogger\Lib;


/**
 * Se charge des operations de base sur les fichiers
 *
 * Ne fait que des traitements fichiers utiles pour les autres libs
 */
class FileLib
{
    /** @var string emplacement du fichier */
    private $_file;


    /**
     * Set les infos concernant le fichiers
     *
     * Path: verif si presence de / en fin
     * Filename: nom du fichier sans extension
     * Ext: extension du fichier avec un .
     * Ajoute le full_path
     *
     * @param string $path le repertoire ou se trouve le fichiers
     * @param string $filename le nom du fichiers
     * @param string $ext extension du fichiers
     *
     * @return void
     */
    public function __construct( $path, $filename, $ext )
    {
        // format
        $path = ( substr($path, -1, 1) === DIRECTORY_SEPARATOR ) ? $path : $path . DIRECTORY_SEPARATOR;
        $filename = ( substr($filename, 0, 1) === DIRECTORY_SEPARATOR ) ? substr($filename, 1, strlen($filename)) : $filename;
        $ext = ( substr($ext, 0, 1) === '.' ) ? $ext : '.' . $ext ;

        // set
        $this->_path = $path;
        $this->_filename = $filename;
        $this->_ext = $ext;
        $this->_full_path= $path . $filename . $ext;
    }

    /**
     * Creation d'un fichiers
     *
     * Se realise si le repertoire est valide @see FileLib::is_valide()
     *
     * @return bool true si le repertoir a etait cree
     */
    public function create_file()
    {
        $ok = false;

        if( $this->is_valide() ) {
            file_put_contents($this->_full_path, '');
            $ok = file_exists($this->_full_path);
	}

        return $ok;
    }

    /**
     * Verifie si le repertoire est ok
     *
     * Il doit juste etre writable
     *
     * @return bool true si il est writable
     */
    public function is_valide()
    {
        return is_writable($this->_full_path) || is_writable($this->_path);
    }

    /**
     * Donne l'emplacement complet du fichiers
     *
     * Ou complet signifie path filename ext
     * @return string l'emplacement du fichier 
     */
    public function get_full_path()
    {
        return $this->_full_path;
    }
}
