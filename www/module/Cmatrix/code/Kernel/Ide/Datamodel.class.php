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
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Ide\Generator\Datamodel as generator;
use \Cmatrix\Kernel\Exception as ex;

class Datamodel extends cm\Kernel\Reflection {
    static $INSTANCES = [];
    
    protected $_Url;
    protected $_Path;
    protected $_Json;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        $Key = get_class($this);
        parent::__construct($Key);
        
        //$this->Path;    // проверить наличие класса датамодели
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){ 
        switch($name){
            case 'Url' : return $this->getMyCode();
            case 'Path' : return $this->getMyPath();
            case 'Json' : return $this->getMyJson();
            case 'Props' : return $this->getMyProps();
            case 'OwnProps' : return $this->getMyOwnProps();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- ---
    protected function init(){
        $this->Data = array_map(function($prop){ return null; },$this->Props);
    }
    
    // --- --- --- --- --- --- --- ---
    /*
    protected function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = substr($this->Url,strpos($this->Url,'/')+1);    // 1. убрать имя модуля (до первого слэша)
            $ClassName = substr($Path,strrpos($Path,'/')+1);        // 2. получить имя класса (после последнего слэша)
            $Path = substr($Path,0,strrpos($Path,'/'));             // 3. получить путь (до последнего слэша)
            
            $Path = Module::get($this->Url)->Path .'/dm/'. $Path;
            if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error($this,'datamodel [' .$this->Url. '] folder is not found.');
            
            $Path = $Path.'/'.$ClassName.'.class.php';
            if(!file_exists($Path)) throw new ex\Error($this,'datamodel [' .$this->Url. '] class is not found.');
            
            return $Path;
        });
    }
    */

    // --- --- --- --- --- --- ---
    // --- --- --- --- --- --- ---
    // --- --- --- --- --- --- ---
    protected function setMyJson(){
        $_parent = function(){
            $Parent = get_parent_class($this);
            return $Parent && $Parent != __CLASS__ ? (new $Parent())->Url : null;
        };
            
        return (generator::get($this->setMyCode())
            ->setParent($_parent())
            ->setName($this->setMyName())
            ->setProps($this->setMyProps())
        )->Data;
    }
    
    // --- --- --- --- --- --- ---
    protected function setMyCode(){
        return str_replace(["\\",'/Datamodel'],['/',null],get_class($this));
    }
    
    // --- --- --- --- --- --- ---
    protected function setMyName(){
        return $this->setMyCode();
    }
    
    // --- --- --- --- --- --- ---
    protected function setMyProps(){
        return (generator\Props::get()
            ->setProp(generator\Prop::get('id','::id::',-1))
            ->setProp(generator\Prop::get('hid','::hid::'))
            ->setProp(generator\Prop::get('pid','::pid::'))
            ->setProp(generator\Prop::get('act','::act::'))
            ->setProp(generator\Prop::get('dlt','::dlt::'))
            ->setProp(generator\Prop::get('hdn','::hdn::'))
        );
    }
    
    // --- --- --- --- --- --- ---
    // --- --- --- --- --- --- ---
    // --- --- --- --- --- --- ---
    protected function getMyJson(){
        //$Key = str_replace("\\",'/',get_class($this));
        //$Key = str_replace('/','_',$Key).'.dm.json';
        //$Key = str_replace('\\','_',get_class($this)).'.dm.json';
        $Key = $this->setMyCode().'.dm.json';
        
        if(ide\Cache::get('dms')->isExists($Key)){
            return ide\Cache::get('dms')->getJsonValue($Key);
        }
        else{
            return $this->setMyJson();
        }
    }
    
    // --- --- --- --- --- --- ---
    protected function getMyCode(){
        return $this->Json['code'];
    }
    
    // --- --- --- --- --- --- ---
    protected function getMyName(){
        return $this->Json['name'];
    }
    
    // --- --- --- --- --- --- --- ---
    // свойства с учётом наследования
    private function getMyProps(){
        return $this->Json['props'];
    }
    
    // --- --- --- --- --- --- --- ---
    // свойства с учётом наследования
    private function getMyOwnProps(){
        return array_filter($this->Props,function($value){
            return !array_key_exists('own',$value) || $value['own'] ? true : false;
        });
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        $_className = function() use($url){
            $Pos = strpos($url,'/');
            $Module = substr($url,0,$Pos);
            $Class = str_replace('/','\\',substr($url,$Pos+1));
            return $Module .'\\Datamodel\\'. $Class;
        };
        
        $ClassName = $_className();
        return new $ClassName();
    }
}





class __Datamodel extends cm\Kernel\Reflection {
    static $INSTANCES = [];
    protected $Url;
    protected $Id;
    
    protected $_Instance;    // instance of /Cmatrix/Orm/Datamodel
    protected $_ClassName;
    protected $_Path;
    
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
            //try{
                $ClassName = $this->ClassName;
                $Ob = new $ClassName($this->Id);
                if(!class_exists($ClassName)) throw new ex\Error($this,'class [' .$ClassName. '] is not defined.');
                return $Ob;
            /*}
            catch(\Throwable $e){
                //throw new \Exception($e->getMessage());
            }
            catch(ex\Error $e){
                throw new \Exception($e->getMessage());
            }*/
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

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function createCache(){
        $Key = $this->Instance->Url.'.dm.json';
        $Value = $this->Instance->Json;
        /*
        $Value = json_encode($this->Instance->Json,
            JSON_PRETTY_PRINT             // форматирование пробелами
            | JSON_UNESCAPED_SLASHES      // не экранировать /
            | JSON_UNESCAPED_UNICODE      // не кодировать текст
        );
        */
        Cache::get('dms')->putJsonValue($Key,$Value);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url,$id=null){
        return new self($url,$id);
    }
    
    // --- --- --- --- --- --- --- ---
    static function cache($url){
        return (new self($url))->createCache();
    }
}
?>