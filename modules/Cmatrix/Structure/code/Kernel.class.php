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
    protected $Models = [];
    protected $Target;
    protected $Provider;
    
    static $TARGETS = ['all','dm','ds'];
    
    // --- --- --- --- --- --- --- ---
    function __construct(iProvider $provider, $target, array $models){
        $this->Models = $models;
        $this->Target = $target;
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
        $Arr = [];
        array_map(function($url) use(&$Arr){
            $Model = \Cmatrix\Structure\Model::get($url,$this->Target);
            $Arr[] = $Model->getSqlCreate($this->Provider);
        },$this->Models);
        
        $Main = $Fk = $Init = [];
        array_map(function($val) use(&$Main,&$Fk,&$Init){
            $Main[] = $val['main'];
            $Init[] = $val['init'];
            $Fk[] = $val['fk'];
        },$Arr);
        
        $Sql = [];
        $Sql[] = implode("\n",array2line($Main))."\n";
        $Sql[] = implode("\n",array2line($Init))."\n";
        $Sql[] = implode("\n",array2line($Fk))."\n";
        
        return implode("\n",$Sql);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @param string $target - цель: all:provider, dm::provider, ds::provider.
     * @param string $url - url сущности
     */
    static function get($target,$url){
        if(!($Provider = strAfter($target,'::'))) $Provider = \Cmatrix\App\Kernel::get()->Config->getValue('db/def/provider','pgsql');
        $Provider = Provider::get($Provider);
        
        $Target = strBefore($target,'::');
        if(!in_array($Target,self::$TARGETS)) throw new ex\Error('target "' .$Target. '" is not valid.');
        
        $Models = \Cmatrix\Structure\Tree::get($Target,$url)->List;
        //dump($Models);die();
        
        return new self($Provider,$Target,$Models);
    }
}
?>