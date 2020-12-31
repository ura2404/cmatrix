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
use \Cmatrix\Kernel\Ide\Generator\Datamodel as generator;
use \Cmatrix\Kernel\Exception as ex;

abstract class __Datamodel extends aDatamodel{
    protected $Id;
    protected $Data = [];
    protected $Changed = [];    // массив изменённых свойств
    
    // --- --- --- --- --- --- --- ---
    function __construct($id=null){
        $this->Id = $id;
        
        $this->init();
    }
    
    // --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Name' : return $this->getMyName();
            case 'Url' : return $this->getMyCode();
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
    
    // --- --- --- --- --- --- ---
    // --- --- --- --- --- --- ---
    // --- --- --- --- --- --- ---
    protected function setMyJson(){
        $_parent = function(){
            $Parent = get_parent_class($this);
            return $Parent && $Parent != __CLASS__ ? (new $Parent())->Url : null;
        };
            
        return (ide\Generator\Datamodel::get($this->setMyCode())
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
        return $this->Url;
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
    // --- --- --- --- --- --- --- ---
    private function getMyJson(){
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
}
?>