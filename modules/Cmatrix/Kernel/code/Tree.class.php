<?php
/**
 * @author ura@itx.su
 * @version 1.0 2021-03-04
 */

namespace Cmatrix\Kernel;
//use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Tree {
    private $Tree = [];
    
    private $Root = null;            // узел, от которого строим дерево
    
    private $NameName = 'name';        // имя ключа для узла
    private $ParentName = 'parent';    // имя ключа для родителя
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * поиск узла в дереве в качестве корня
     */
    private function getRootNode($root=null){
        $_rec = function($tree,&$ret=null) use($root,&$_rec){
            foreach($tree as $node=>$children){
                if($root === $node) $ret = $children;
                else $_rec($children,$ret);
            }
            return $ret;
        };
        
        $Node = $_rec($this->Tree);
        if($Node === null) throw new ex\Error('node not found in the tree.');
        return $Node;
        
        
        //dump($_rec($this->Tree));
        return $_rec($this->Tree);
    }    
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * Формирует сортированный по иерархии список узлов
     */
    public function getPlainTree($root=null){
        $_rec = function($tree,&$ret=[]) use(&$_rec){
            foreach($tree as $node=>$children){
                $ret[] = $node;
                $_rec($children,$ret);
            }
            return $ret;
        };
        
        return $_rec($root ? $this->getRootNode($root) : $this->Tree);
    }
    
    // --- --- --- --- --- --- --- ---
    public function createTreeFromList(array $tree){
        $Roots = array_filter($tree,function($val){ return $val[$this->ParentName] == null ? true : false; });
        if(!$Roots){
            // здесь определяем мнимые корневые узлы - узлы с несуществующим парентом
            $Parents = array_unique(array_column($tree,$this->ParentName));
            $Nodes = array_column($tree,$this->NameName);
            $Roots = array_diff($Parents,$Nodes);
            $Roots = array_map(function($val) use($Roots){ return in_array($val[$this->ParentName],$Roots) ? $val[$this->NameName] : null; },$tree);
            $Roots = array_filter($Roots,function($val){ return !!$val; });
        }
        else $Roots = array_map(function($val){ return $val[$this->NameName]; },$Roots);
        
        $_rec = function($root) use(&$_rec,$tree){
            $Arr = [];
            $Children = array_filter($tree,function($val) use($root){ return $val[$this->ParentName] === $root; });
            array_map(function($val) use(&$Arr,$_rec){
                $name = $val[$this->NameName];
                $Arr[$name] = $_rec($name);
            },$Children);
            return $Arr;
        };
        
        foreach($Roots as $root){
            $this->Tree[$root] = $_rec($root);
        }
        
        //dump($this->Tree);
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function create(array $tree){
        $Tree = new self();
        $Tree->createTreeFromList($tree);
        return $Tree;
        
        return new self();
    }
}
?>