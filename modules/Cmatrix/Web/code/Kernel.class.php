<?php
/**
 * Class Kernel
 *
 * @author ura@itx.ru
 * @version 1.0 2020-11-11
 */

namespace Cmatrix\Web;
use \Cmatrix\Kernel\Exception as ex;

class Kernel extends \Cmatrix\Kernel\Reflection {
    //static $INSTANCES = [];
    
    protected $_Config;
    protected $_Home;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct('web.kernel');
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Config' : return $this->getMyConfig();
            case 'Home' : return $this->getMyHome();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - whome
     */
    protected function getMyHome(){
        return $this->getInstanceValue('_Home',function(){
            if(!($Home = Kernel::get()->Config->getValue('web/root'))) throw new ex\Error($this,'configure variable "web.root" is not defined.');
            return $Home;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return \Cmatrix\Kernel\Config - конфиг
     */
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            $Path = CM_ROOT.CM_DS.'app'.CM_DS.'web.config.json';
            $PathSrc = \Cmatrix\Kernel\Ide\Part::get('Cmatrix/Web')->Path.CM_DS.'web.config.json';
            
            if(!file_exists($Path) && !file_exists($PathSrc)) die('cannot open web.config.file');
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
    public function makeHtaccess(){
        $Src = \Cmatrix\Kernel\Ide\Part::get('Cmatrix/Web')->Path.CM_DS.'src'.CM_DS.'.htaccess';
        $Dst = CM_ROOT.CM_DS.'www'.CM_DS.'.htaccess';
        copy($Src,$Dst);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}
?>