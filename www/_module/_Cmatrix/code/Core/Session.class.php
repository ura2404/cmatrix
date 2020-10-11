<?php
/**
 * @author ura@itx.su
 * @version 1.0 2020-08-14
 */

namespace Cmatrix\Core;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Orm as orm;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

class Session extends cm\Kernel\Reflection {
    static $INSTANCES = [];
    protected $_Hid;
    protected $_Instance;
    protected $_CookieName;
    protected $_Cookie;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        $Key = 'current';
        
        parent::__construct($Key);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Hid' : return $this->getMyHid();
            case 'Instance' : return $this->getMyInstance();
            case 'CookieName' : return $this->getMyCookieName();
            case 'Cookie' : return $this->getMyCookie();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    function __set($name,$value){
        switch($name){
            //case 'Hid' : $this->setMyHid(); break;
            default : return parent::__set($name,$value);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyInstance(){
        return $this->getInstanceValue('_Instance',function() {
            $Dm = ide\Datamodel::get('Cmatrix/Core/Session');
            if(kernel\Kernel::$DB){
                $Ob = orm\Ob::get($Dm,[ 'hid' => $this->Hid ]);
            }
            else{
                $Ob = orm\Ob::get($Dm);
                
                /*
                $Ob = kernel\Ide\Datamodel::get('Cmatrix/Core/Session',[ 'hid' => $this->Hid ])->Instance;
                //$Ob = new \Cmatrix\Datamodel\Core\Session([ 'hid' => $this->Hid ]);
                dump($Ob);
                //dump($Ob->Props);
                return $Ob;
                */
            }
            return $Ob;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyCookieName(){
        return $this->getInstanceValue('_CookieName',function(){
            return 'cmapp_'.str_replace(['.','-'],'_',kernel\Config::get()->getValue('app/code'));
        });
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyCookie(){
        return $this->getInstanceValue('_Cookie',function(){
            if(!empty($_COOKIE[$this->CookieName])) return $_COOKIE[$this->CookieName];
            else $this->setMyCookie();
        });
    }
    
    // --- --- --- --- --- --- --- ---
    protected function setMyCookie(){
        //$Path = kernel\Kernel::$WHOME ? kernel\Kernel::$WHOME : '/';
        $Path = '/';
        $Days = 1;
        
        $Period = time() + ($Days * 86400);
        
        setcookie($this->CookieName,hid(),$Period,$Path);
        setcookie($this->CookieName.'_ts',time(),$Period,$Path);
        
        cm\Web\Page::reload();
        exit();
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyHid(){
        return $this->getInstanceValue('_Hid',function(){
            if(kernel\Kernel::$SAPI === 'CLI') return md5('cmapp_cli');
            elseif(kernel\Kernel::$SAPI === 'APACHE') return $this->Cookie;
            else return md5('NULL');
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