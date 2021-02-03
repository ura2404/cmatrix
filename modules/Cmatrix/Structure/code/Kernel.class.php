<?php
/**
 * Class \Cmatrix\Structure\Kernel
 * 
 * @author ura@itx.ru 
 * @version 1.0 2021-02-01
 */
 
namespace Cmatrix\Structure;
//use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Kernel {
    protected $Model;
    protected $Provider;
    
    // --- --- --- --- --- --- --- ---
    function __construct(iProvider $provider, iModel $model){
        $this->Model = $model;
        $this->Provider = $provider;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'SqlCreate' : return $this->getMySqlCreate();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMySqlCreate(){
        return $this->Model->getSql($this->Provider);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /*static function get(iModel $model,iProvider $provider){
        return new self($model,$provider);
    }*/
    static function get($target,$url){
        if(!($Provider = strAfter($target,'::'))) $Provider = \Cmatrix\App\Kernel::get()->Config->getValue('db/def/provider','pgsql');
        $Provider = Provider::get($Provider);
        
        $Target = strBefore($target,'::');
        $Model = \Cmatrix\Structure\Model::get($url,$Target);
        
        return new self($Provider,$Model);
    }
}
?>