<?php
/**
 * Class Cmatrix\Structure\Datamodel
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-29
 */
 
namespace Cmatrix\Structure;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Datamodel {
    protected $Url;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            //case 'Sql' : return $this->getMySql();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /*private function getMySql(){
        $Module = kernel\Url::get($this->Url)->Part1;
        $Part = kernel\Url::get($this->Url)->Part2;
        $Entity = kernel\Url::get($this->Url)->Part3;
        
        if($Module && $Part && $Entity){
            $Dm = kernel\Ide\Datamodel::get($this->Url);
            $Sql = ($Dm->Parent ? self::get($Dm->Parent->Url)->Sql : null). Datamodel\Sql::get($Dm)->Sql;
        }
        elseif($Module && $Part && !$Entity){
        }
        elseif($Module && !$Part && !$Entity){
        }
        elseif(!$Module && !$Part && !$Entity) throw new ex\Error('bad url "' .$this->Url. '"for generate SQL script');
    }*/
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function getSql($provider){
        $ClassName = '\Cmatrix\Structure\Provider\\' . ucfirst($provider) . '\Datamodel';
        $Dm = kernel\Ide\Datamodel::get($this->Url);
        return $ClassName::get($Dm)->Sql;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>