<?php
/**
 * Class Datamodel
 * 
 * Класс для датамоделей сущностей
 *
 * @author ura@itx.ru
 * @version 1.0 2020-08-18
 */

namespace Cmatrix\Orm;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

abstract class Datamodel extends aDatamodel{
    protected $Id;
    protected $Data = [];
    protected $Changed = [];    // массив изменённых свойств
    
    protected $_Props;
    
    // --- --- --- --- --- --- --- ---
    function __construct($id=null){
        $this->Id = $id;
        
        $this->init();
    }
    
    // --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Name' : return $this->getMyName();
            case 'Url' : return $this->getMyUrl();
            case 'Json' : return $this->getMyJson();
            case 'Props' : return $this->getMyProps();
            case 'OwnProps' : return $this->getMyOwnProps();
            default : 
                $MyName = $name{0} === '_' ? substr($name,1) : $name;
                if(!array_key_exists($MyName,$this->Props)) throw new ex\Error($this,'property ['.$MyName.'] of entity ['.$this->Url.'] is not defined.');
                
                if($name{0} === '_' and array_key_exists($MyName,$this->Changed)) return $this->Changed[$MyName];
                else return $this->Data[$MyName];
        }
    }
    
    // --- --- --- --- --- --- ---
    protected function init(){
        $this->Data = array_map(function($prop){ return null; },$this->Props);
    }
    
    // --- --- --- --- --- --- --- ---
    protected function createJson(){
        return [
            'code' => $this->Url,
            'name' => $this->Name,
            'props' => [
                'id' => [],
                'parent_id' => [],
                'chain_id' => [],
                'status' => [],
                'active' => [],
                'hidden' => [],
                'deleted' => [],
                'info' => [],
            ]
        ];
    }

    // --- --- --- --- --- --- ---
    protected function getMyName(){
        return $this->Url;
    }

    // --- --- --- --- --- --- ---
    protected function getMyUrl(){
        return str_replace(["\\",'/Datamodel'],['/',null],get_class($this));
    }

    // --- --- --- --- --- --- ---
    protected function getMyParentUrl(){
        $Parent = get_parent_class($this);
        return $Parent ? (new $Parent())->Url : null;
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyJson(){
        $Key = str_replace('/','_',$this->Url).'.dm.json';
        
        if(ide\Cache::get('dms')->isExists($Key)){
            return ide\Cache::get('dms')->getValue($Key);
        }
        
        else{
            return $this->createJson();
            
            //dump(get_class($this));
            //$ClassName = $this->ClassName;
            //return $ClassName::json();
        }
        
        /*
        return $this->getInstanceValue('_Json',function(){
            $Key = str_replace('/','_',$this->Url).'.dm.json';
            
            if(Cache::get('dms')->isExists($Key)){
                return ide\Cache::get('dms')->getValue($Key);
            }
            
            else{
                $ClassName = $this->ClassName;
                return $ClassName::json();
            }
        });
        */
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
}
?>