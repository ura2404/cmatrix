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

class Session {
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
        if(app\Kernel::get()->Config->getValue('db')){
            
        }
        else{
            $Dm = orm\Datamodel::get('Cmatrix/Core/Session');
            //dump($Dm);
            
            $Ob = orm\Entity::create($Dm);
            dump($Ob);
            
            
            //return orm\Datamodel::get('Cmatrix/Core/Session');
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