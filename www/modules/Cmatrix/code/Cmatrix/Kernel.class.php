<?php
/**
 * @author ura@itx.su
 * @version 1.0 2020-07-21
 */

namespace Cmatrix;

class Kernel {
    static $INSTANCE = null;

    static $HOME;

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
            return strBefore($Path,'/modules/Cmatrix');
        };

        self::$HOME = $_home();
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}

?>