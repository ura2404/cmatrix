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
    static $WHOME;
    static $TMP;
    static $PAGE;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        if(!self::$INSTANCE) $this->createInstance();
    }


    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function createInstance(){
        $Config = null;
        $_home = function() use(&$Config){
            $Path = __FILE__;
            $Path = str_replace(DIRECTORY_SEPARATOR,'/',dirname(__FILE__));
            $Path = strBefore($Path,'/module/Cmatrix');
            return $Path;
        };
        
        $_config = function(){
            $Path = self::$HOME .'/config/app.json';
            if(!file_exists($Path)) throw new ex\Error($this,"application config file is not defined.");
            $Config = json_decode(file_get_contents($Path),true);
            return $Config;
        };
        
        $_whome = function($сonfig){
            if(!isset($сonfig['web']) || !isset($сonfig['web']['home'])) throw new ex\Error($this,"configure variable 'web.home' is not defined.");
            return $сonfig['web']['home'];
        };
        
        $_tmp = function($сonfig){
            if(!isset($сonfig['env']) || !isset($сonfig['env']['tmp'])) throw new ex\Error($this,"configure variable 'env.tmp' is not defined.");
            return $сonfig['env']['tmp'];
        };
        
        self::$HOME  = $_home();     // обязательно первым
        $Config = $_config();        // обязательно вторым
        self::$WHOME = $_whome($Config);
        self::$TMP   = $_tmp($Config);
        self::$INSTANCE = $this;     // обязательно последним
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}

?>