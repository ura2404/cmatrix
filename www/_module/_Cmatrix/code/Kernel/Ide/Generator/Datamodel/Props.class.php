<?php
/**
 * Class Props
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-28
 */

namespace Cmatrix\Kernel\Ide\Generator\Datamodel;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

class Props {
    private $Props = [];
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Array' : return $this->Props;
            case 'Data': return $this->getMyData();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyData(){
        $Arr = array_map(function($value){ return $value->Data; },$this->Props);
        
        usort($Arr,function($v1,$v2){
            if($v1['order'] == $v2['order']) return 0;
            return $v1['order'] < $v2['order'] ? -1 : 1;
        });
        
        $Arr = array_combine(array_column($Arr,'code'), $Arr);
        
        return $Arr;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @params bool @own - true|false - собственные ли добавляемые поля
     */
    public function setProps(Props $props,$own=null){
        $Props = array_map(function($value) use($own){ $value->setOwn($own ? $own : false); return $value; },$props->Array);
        
        $this->Props = array_merge($this->Props,$Props);
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    public function setProp(Prop $prop){
        $this->Props[$prop->Code] = $prop;
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}