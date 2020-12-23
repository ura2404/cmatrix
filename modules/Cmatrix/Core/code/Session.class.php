<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-12-09
 */

namespace Cmatrix\Core;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\App as app;
use \Cmatrix\Orm as orm;
use \Cmatrix\Core as core;

class Session {
    static $INSTANCE;
    
    private $_Ident;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        if(!self::$INSTANCE) self::$INSTANCE = $this->createInstance();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Instance' : return self::$INSTANCE;
            case 'Ident' : return $this->getMyIdent();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function createInstance(){
        if(app\Kernel::get()->isDb){
            // ---- создать сессию с БД
            
        }
        else{
            // --- создать сессию без БД
            $Dm = orm\Datamodel::get('Cmatrix/Core/Session');
            $Ob = orm\Entity::create($Dm);
            
            $Init = kernel\Ide\Datamodel::get('Cmatrix/Core/Session')->Init;
            $Init = array_filter($Init,function($val){ return !!($val['agent'] === 'Console'); });
            $Session = array_shift($Init);
            
            if($Session) $Ob->setValues($Session);
            
            $Ob->hid = app\Kernel::get()->Hid;
            $Ob->create_ts = date('Y-m-d H:i:s',app\Kernel::get()->Ts);
            $Ob->session_id = $Ob->id;
            $Ob->sysuser_id = core\Sysuser::get()->id;
            $Ob->setValues($this->Ident);
            
            //dump($Ob);
            
            return $Ob;
        }
    }

    // --- --- --- --- --- --- --- ---
    /**
     * @return array - unique session identificator
     */
    private function getMyIdent(){
        if($this->_Ident) return $this->_Ident;
        
        $Arr = [];
        
        if(app\Kernel::get()->Sapi === 'CLI'){
            $Arr['ip4'] = 'x.x.x.x';
            $Arr['agent'] = 'Cmatrix console';
        }
        else{
            $Arr['ip4']      = isset($_SERVER['REMOTE_ADDR'])          ? $_SERVER['REMOTE_ADDR']          : NULL;
            $Arr['ip4x']     = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : NULL;
            $Arr['proxy']    = isset($_SERVER['HTTP_VIA'])             ? $_SERVER['HTTP_VIA']             : NULL;
            $Arr['agent']    = isset($_SERVER['HTTP_USER_AGENT'])      ? $_SERVER['HTTP_USER_AGENT']      : NULL;
            $Arr['lang']     = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : NULL;
            $Arr['charset']  = isset($_SERVER['HTTP_ACCEPT_CHARSET'])  ? $_SERVER['HTTP_ACCEPT_CHARSET']  : NULL;
            $Arr['encoding'] = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : NULL;
            
            if($Arr['ip4'] === '::1') $Arr['ip4'] = '127.0.0.1';
        }		
		return $this->_Ident = $Arr;
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        new self();
        return self::$INSTANCE;
    }
}
?>