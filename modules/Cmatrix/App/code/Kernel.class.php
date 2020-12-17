<?php
/**
 * Class Cmatrix\App\Kernel
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-17
 */

namespace Cmatrix\App;
use \Cmatrix\Kernel\Exception as ex;

class Kernel extends \Cmatrix\Kernel\Reflection {
    static $INSTANCES = [];
    
    protected $_Sapi;
    protected $_Config;
    protected $_Hid;
    protected $_Cookie;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct('app.kernel');
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Sapi'   : return $this->getMySapi();
            case 'Config' : return $this->getMyConfig();
            case 'Hid'    : return $this->getMyHid();
            case 'Cookie' : return $this->getMyCookie();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMySapi(){
        return $this->getInstanceValue('_Sapi',function(){
            $Sapi = php_sapi_name();
            if($Sapi=='cli') return 'CLI';
            elseif(substr($Sapi,0,3)=='cgi') return 'CGI';
            elseif(substr($Sapi,0,6)=='apache') return 'APACHE';
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return \Cmatrix\Kernel\Config - конфиг приложения
     */
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            $Path = CM_ROOT.CM_DS.'app'.CM_DS.'config.json';
            return $Config = \Cmatrix\Kernel\Config::get($Path);
        });
    }

    // --- --- --- --- --- --- --- ---
    /**
     * @return string - application cookie name
     */
    private function getMyCookie(){
        return $this->getInstanceValue('_Cookie',function(){
            return str_replace('.','-',$this->Config->getValue('app/code','cmatrix').'-'.hid('_application'));
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - 32 symbol hash
     */
    private function getMyHid(){
        return $this->getInstanceValue('_Hid',function(){
            switch($this->Sapi){
                case 'CLI' : return md5('Cmatrix cli');
                case 'APACHE' :
                    $Hid = empty($_COOKIE[$this->Cookie]) ? null : $_COOKIE[$this->Cookie];
                    if(!$Hid) $this->setCookie($this->Cookie,hid());
                    else return $Hid;
                    break;
                    
                default : die('Can\'t calculate application hid.');
            }
        });
    }
    
    // --- --- --- --- --- --- ---
    private function setCookie($name,$hid){
        switch($this->Sapi){
            case 'CLI' : return;
            case 'APACHE' :
                // выставить куку на период $Period для url $Url
                
                // $Period = 86400      // день    - 60*60*24
                // $Period = 604800     // неделя  - 60*60*24*7
                // $Period = 2592000    // 30 дней - 60*60*24*30
                // $Period = 31536000   // год     - 60*60*24*365
                $Period = 5;
                
                $Url = '/';
                
                $Time = time();
                setcookie($name,$hid,$Time + $Period,$Url);
                setcookie($name.'_ts',$Time,$Time + $Period,$Url);
                
                $this->reload();
                exit();        
                break;
                
            default : die('Can\'t set application cookie.');
        }
    }
    
    // --- --- --- --- --- ---
    private function unsetCookie($name){
        switch($this->Sapi){
            case 'CLI' : return;
            case 'APACHE' :
                $Period = 31536000;
                
                $Url = '/';
                
                $Time = time();
                setcookie($name,"",$Time + $Period,$Path2);
                setcookie($name.'_ts',"",$Time + $Period,$Path2);
                
                $this->reload();
                exit();        
                break;
                
            default : die('Can\'t unset application cookie.');
        }
    }

    // --- --- --- --- --- ---
    private function reload(){
        switch($this->Sapi){
            case 'CLI' : return;
            case 'APACHE' :
                $Proto = strtolower(strBefore($_SERVER['SERVER_PROTOCOL'],'/'));
                $Host = $_SERVER['HTTP_HOST'];
                $Uri = $_SERVER['REQUEST_URI'];
                
                $Url = $Proto.'://'.$Host.$Uri;
                header("Location: ".$Url);
                break;
                
            default : die('Can\'t unset application cookie.');
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}
?>