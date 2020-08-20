<?php
/**
 * Class Datamodel
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-16
 */

namespace Cmatrix\Kernel\Ide;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Datamodel extends cm\Kernel\Reflection {
    static $INSTANCES = [];
    protected $Url;
    protected $Id;
    
    protected $_Instance;
    protected $_ClassName;
    protected $_Path;
    protected $_Json;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url,$id=null){
        kernel\Kernel::get();
        
        $this->Url = $url;
        $this->Id = $id;
        
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'createCache' : return $this->createMyCache();
            case 'Path' : return $this->getMyPath();
            case 'ClassName' : return $this->getMyClassName();
            case 'Instance' : return $this->getMyInstance();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMyInstance(){
        return $this->getInstanceValue('_Instance',function(){
            try{
                $ClassName = $this->ClassName;
                $Ob = new $ClassName($this->Id);
                if(!class_exists($ClassName)) throw new ex\Error($this,'class [' .$ClassName. '] is not defined.');
                return $Ob;
            }
            catch(\Throwable $e){
                //throw new \Exception($e->getMessage());
            }
            catch(ex\Error $e){
                throw new \Exception($e->getMessage());
            }
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyClassName(){
        return $this->getInstanceValue('_ClassName',function(){
            $Pos = strpos($this->Url,'/');
            $Module = substr($this->Url,0,$Pos);
            $Class = str_replace('/','\\',substr($this->Url,$Pos+1));
            return $Module .'\\Datamodel\\'. $Class;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = substr($this->Url,strpos($this->Url,'/')+1);    // 1. убрать имя модуля (до первого слэша)
            $ClassName = substr($Path,strrpos($Path,'/')+1);        // 2. получить имя класса (после последнего слэша)
            $Path = substr($Path,0,strrpos($Path,'/'));             // 3. получить путь (до последнего слэша)
            
            $Path = Module::get($this->Url)->Path .'/dm/'. $Path;
            if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error($this,'datamodel [' .$this->Url. '] folder is not found.');
            if(!file_exists($Path.'/'.$ClassName.'.class.php')) throw new ex\Error($this,'datamodel [' .$this->Url. '] class is not found.');
            
            return $Path;
        });
    }

    private function createMyCache(){
        dump($this->Instance->Json);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url,$id=null){
        return new self($url,$id);
    }
    
    // --- --- --- --- --- --- --- ---
    static function cache($url){
        return (new self($url))->createCache;
    }
}
?>