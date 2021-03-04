<?php
/**
 * Class \Cmatrix\Structure\Tree
 * 
 * @author ura@itx.ru 
 * @version 1.0 2021-03-04
 */
 
namespace Cmatrix\Structure;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Tree {
    protected $Target;
    protected $Url;
    
    // --- --- --- --- --- --- --- ---
    function __construct($target,$url){
        $this->Target = $target;
        $this->Url = $url;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'List' : return $this->getMyList();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return array - список сущностоей в порядке наследования
     */
    protected function getMyList(){
        $Url = kernel\Url::get($this->Url);
        
        $_src = function($part){
            if($this->Target = 'dm') return kernel\Ide\Part::get($part)->Datamodels;
            elseif($this->Target = 'ds') return kernel\Ide\Part::get($part)->Datasources;
            elseif($this->Target = 'all') return [];
        };
        
        $_node = function($url){
            if($this->Target = 'dm'){
                $Datamodel = kernel\Ide\Datamodel::get($url);
                $Parent = $Datamodel->Parent;
                return [
                    'name'   => $Datamodel->Json['code'],
                    'parent' => $Parent ? $Parent->Json['code'] : null
                ];
            }
            elseif($this->Target = 'ds') return [];
            elseif($this->Target = 'all') return [];
        };
        
        $_part = function($part) use($_src,$_node){
            $Tree = [];
            array_map(function($url) use($_node,&$Tree){
                $_fun = $_node($url);
                $Tree[] = $_node($url);
            },$_src($part));
            return (new kernel\Tree())->createTreeFromList($Tree)-> getPlainTree();
        };
        
        $_module = function($module) use($_part){
            $Tree = [];
            array_map(function($val) use($_part,&$Tree){
                $Tree = array_merge($Tree,$_part($val));
            },kernel\Ide\Module::get($module)->Parts);
            return $Tree;
        };
        
        if($Url->Part3) return [$this->Url];
        elseif($Url->Part2) return $_part($this->Url);
        elseif($Url->Part1) return $_module($this->Url);
        else throw new ex\Error('wrong url "' .$this->Url. '"for structure tree.');
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($target,$url){
        return new self($target,$url);
    }
}
?>