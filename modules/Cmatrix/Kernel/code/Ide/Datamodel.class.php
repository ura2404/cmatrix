<?php
/**
 * Class Cmatrix\Kernel\Ide\Datamodel
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-03
 */
 
namespace Cmatrix\Kernel\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Datamodel extends kernel\Reflection{
    //static $INSTANCES = [];

    protected $Url;
    
    protected $_Path;
    protected $_Class;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            //case 'Url'   : return $this->Url;
            case 'Path'  : return $this->getMyPath();
            case 'Class' : return $this->getMyClass();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - path to datamodel description file
     */
    protected function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            dump($this->Url,'URL 1');
            $Path = kernel\Ide\Part::get($this->Url)->Path.CM_DS.'dm'.CM_DS.kernel\Url::get($this->Url)->Path.'.class.php';
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - datamodel class name
     */
    protected function getMyClass(){
        return $this->getInstanceValue('_Class',function(){
            return kernel\Url::get($this->Url)->Module.'\\'.kernel\Url::get($this->Url)->Part.'\\Dm\\'.kernel\Url::get($this->Url)->Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>