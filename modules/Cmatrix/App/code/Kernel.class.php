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
    protected $_Sapi;
    protected $_Hid;
    protected $_Cts;
    protected $_Tts;
    protected $_Cookie;
    protected $_isDb;
    protected $_Config;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct('app.kernel');
        
        $this->Hid;
        
        //dump(self::$REFINSTANCES);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Hid'    : return $this->getHid();
            case 'Cookie' : return $this->getCookieName();
            case 'Cts'    : return $this->getCts();
            case 'Tts'    : return $this->getTts();
            case 'isDb'   : return $this->getIsDb();
            
            case 'Sapi'   : return $this->getMySapi();
            case 'Config' : return $this->getMyConfig();
            
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - application cookie name
     */
    private function getCookieName(){
        return $this->getInstanceValue('_Cookie',function(){
            return str_replace('.','-',$this->Config->getValue('app/code','cmatrix').'-'.hid('_application'));
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - 32 symbol hash
     */
    private function getHid(){
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
    /**
     * @return timestamp - время создание сессии
     */
    private function getCts(){
        return $this->getInstanceValue('_Cts',function(){
            switch($this->Sapi){
                case 'CLI' : return;
                case 'APACHE' :
                    $Ts = empty($_COOKIE[$this->Cookie.'_cts']) ? null : $_COOKIE[$this->Cookie.'_cts'];
                    return $Ts;
                    
                default : die('Can\'t calculate application cts.');
            }
        });
    }

    // --- --- --- --- --- --- ---
    /**
     * @return timestamp - время создание сессии
     */
    private function getTts(){
        return $this->getInstanceValue('_Tts',function(){
            switch($this->Sapi){
                case 'CLI' : return;
                case 'APACHE' :
                    $Ts = empty($_COOKIE[$this->Cookie.'_tts']) ? null : $_COOKIE[$this->Cookie.'_tts'];
                    return $Ts;
                    
                default : die('Can\'t calculate application tts.');
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
                $Period = 86400;
                
                $Url = '/';
                
                $Time = time();
                setcookie($name,$hid,$Time + $Period,$Url);
                setcookie($name.'_cts',$Time,$Time + $Period,$Url);
                setcookie($name.'_tts',$Time,$Time + $Period,$Url);
                
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
                setcookie($name.'_tts',"",$Time + $Period,$Path2);
                
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
    /**
     * @return bool - признак использования базы данных
     */
    private function getIsDb(){
        //dump(self::$REFINSTANCES[$this->RefKey]);
        return $this->getInstanceValue('_isDb',function(){
            $Config = \Cmatrix\Db\Kernel::get()->Config->getValue('db/def');
            return !!$Config;
            return $Config && count($Config);
        });        
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - режим работы проекта
     *     -CLI     - консоль 
     *     -CGI     - cgi
     *     -APACHE  - модуль apache
     */
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
     * @return \Cmatrix\Kernel\Config
     */
    protected function initConfig($path,$pathSrc){
        copy($pathSrc,$path);
        chmod($path,0660);
        
        $Content = \Cmatrix\Kernel\Json::decode(file_get_contents($path));
        if($Content['app']['code'] !== 'cmatrix'){
            $Content['app']['info'] = null;
            $Content['app']['author'] = null;
            $Content['app']['url'] = null;
            $Content['app']['since'] = date("Y");
            $Content['app']['version'] = '1.0';
            file_put_contents($path,\Cmatrix\Kernel\Json::encode($Content));
        }
    }
     
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            $Path = CM_ROOT.CM_DS.'app'.CM_DS.'app.config.json';
            $PathSrc = \Cmatrix\Kernel\Ide\Part::get('Cmatrix/App')->Path.CM_DS.'app.config.json';
            
            if(!file_exists($Path) && !file_exists($PathSrc)) die('cannot open app.config.file');
            elseif(!file_exists($Path) && file_exists($PathSrc)) $this->initConfig($Path,$PathSrc);
            /*{
                $Content = \Cmatrix\Kernel\Json::decode(file_get_contents($PathSrc));
                
                $Content['app']['info'] = null;
                $Content['app']['author'] = null;
                $Content['app']['url'] = null;
                $Content['app']['since'] = date("Y");
                $Content['app']['version'] = '1.0';
                
                file_put_contents($Path,\Cmatrix\Kernel\Json::encode($Content));
                
                //$old = umask(0);
                //copy($PathSrc,$Path);
                chmod($Path,0660);
                //chown($Path,'www-data');
                //chgrp($Path,'www-data');
                //umask($old);
            }*/
            return $Config = \Cmatrix\Kernel\Config::get($Path);
        });
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}
?>