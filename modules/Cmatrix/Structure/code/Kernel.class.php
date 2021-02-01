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
    function __construct(iModel $model, iProvider $provider){
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
    static function get(iModel $model,iProvider $provider){
        return new self($model,$provider);
    }
}
?>