<?php
/**
 * @author ura@itx.su
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Kernel {
    static $INSTANCE = null;

    static $HOME;
    static $TMP;

    // --- --- --- --- --- --- --- ---
    function __construct(){
        if(!self::$INSTANCE) $this->createInstance();
    }


    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function createInstance(){
        $_home = function(){
            $Path = __FILE__;
            $Path = str_replace(DIRECTORY_SEPARATOR,'/',dirname(__FILE__));
            return strBefore($Path,'/module/Cmatrix');
        };
        
        $_tmp = function(){
            $Path = self::$HOME .'/config/app.json';
            if(!file_exists($Path)) throw new ex\Error($this,"application config file is not defined.");
            
            $Json = json_decode(file_get_contents($Path),true);
            if(!isset($Json['env']) || !isset($Json['env']['tmp'])) throw new ex\Error($this,"environment variable 'tmp' is not defined.");
            
            return $Json['env']['tmp'];
        };
        
        self::$HOME = $_home();     // обязательно первым
        self::$TMP = $_tmp();
        self::$INSTANCE = $this;    // обязательно последним
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}

?>