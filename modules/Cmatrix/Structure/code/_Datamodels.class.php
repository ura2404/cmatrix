<?php
/**
 * Class \Cmatrix\Structure\Datamodels
 * 
 * Реализует механизм создания управляющих скриптов для провайдера БД
 * позволяет обработать модуль, часть или единичную сущность
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-29
 */
 
namespace Cmatrix\Structure;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Datamodels {
    protected $Url;
    protected $Provider;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url,$provider){
        $this->Url = $url;
        $this->Provider = $provider;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Sql' : return $this->getMySql();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMySql(){
        $Module = kernel\Url::get($this->Url)->Part1;
        $Part = kernel\Url::get($this->Url)->Part2;
        $Entity = kernel\Url::get($this->Url)->Part3;
        
        // для сущности
        if($Module && $Part && $Entity){
            $Model = kernel\Ide\Datamodel::get($this->Url);
            $Provider = Provider\Datamodel::get($this->Provider);
            
            $Sql = ($Model->Parent ? self::get($Model->Parent->Url,$this->Provider)->Sql : null) . Model\Datamodel::get($Model,$Provider)->Sql;
        }
        // для части
        elseif($Module && $Part && !$Entity){
        }
        // для модуля
        elseif($Module && !$Part && !$Entity){
        }
        elseif(!$Module && !$Part && !$Entity) throw new ex\Error('bad url "' .$this->Url. '"for generate SQL script');
        
        return $Sql;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url,$provider){
        return new self($url,$provider);
    }
}
?>