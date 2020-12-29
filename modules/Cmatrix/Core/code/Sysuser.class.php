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

class Sysuser {
    static $INSTANCE;    
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        if(!self::$INSTANCE) self::$INSTANCE = $this->createInstance();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Instance' : return self::$INSTANCE;
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function createInstance(){
        if(app\Kernel::get()->isDb){
            // ---- создать пользователя с БД
        }
        else{
            // ---- создать пользователя без БД
            $Dm = orm\Datamodel::get('Cmatrix/Core/Sysuser');
            $Ob = orm\Entity::create($Dm);
            
            $Init = kernel\Ide\Datamodel::get('Cmatrix/Core/Sysuser')->Init;
            $Init = array_filter($Init,function($val){ return !!($val['code'] === 'guest'); });
            $User = array_shift($Init);
            //dump($User);
            
            if($User) $Ob->setValues($User);
            //dump($Ob);
            
            return $Ob;
        }
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