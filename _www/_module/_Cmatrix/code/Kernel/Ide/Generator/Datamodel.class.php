<?php
/**
 * Class Datamodel
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-28
 */

namespace Cmatrix\Kernel\Ide\Generator;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

class Datamodel {
    private $Code;
    
    private $_Name;
    private $_Parent;
    private $_Props;
    
    // --- --- --- --- --- --- --- ---
    function __construct($code){
        $this->Code = $code;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Data': return $this->getMyData();
            case 'Name': return $this->getMyName();
            case 'Parent': return $this->getMyParent();
            case 'Props': return $this->getMyProps();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyData(){
        return [
            'code' => $this->Code,
            'name' => $this->Name,
            'parent' => $this->Parent,
            'props' => $this->Props
        ];
    }

    // --- --- --- --- --- --- --- ---
    private function getMyName(){
        return $this->_Name ? $this->_Name : $this->Code;
    }

    // --- --- --- --- --- --- --- ---
    private function getMyParent(){
        return $this->_Parent ? $this->_Parent : null;
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyProps(){
        return $this->_Props ? $this->_Props->Data : [];
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function setName($name){
        $this->_Name = $name;
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    public function setParent($url){
        $this->_Parent = $url;
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    public function setProps($props){
        $this->_Props = $props;
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($code){
        return new self($code);
    }
}
?>