<?php
/**
 * Class Kernel
 *
 * @author ura@itx.ru
 * @version 1.0 2021-01-24
 */

namespace Cmatrix\Db;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Db as db;

class Kernel extends \Cmatrix\Kernel\Reflection {
    protected $_Config;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct('db.kernel');
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Config' : return $this->getMyConfig();
            case 'CurConfig' : return $this->getMyCurConfig();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @return \Cmatrix\Kernel\Config - конфиг
     */
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            $Path = CM_ROOT.CM_DS.'app'.CM_DS.'db.config.json';
            $PathSrc = \Cmatrix\Kernel\Ide\Part::get('Cmatrix/Db')->Path.CM_DS.'db.config.json';
            
            if(!file_exists($Path) && !file_exists($PathSrc)) die('cannot open db.config.file');
            elseif(!file_exists($Path) && file_exists($PathSrc)){
                //$old = umask(0);
                copy($PathSrc,$Path);
                chmod($Path,0660);
                //chown($Path,'www-data');
                //chgrp($Path,'www-data');
                //umask($old);
            }
            return $Config = \Cmatrix\Kernel\Config::get($Path);
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyCurConfig(){
        return \Cmatrix\Kernel\Config::reg('db.current',$this->Config->getValue('db/_def'));
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}
?>