<?php
/**
 * Class Cmatrix\Orm\Cql\Rules
 *
 * [
 *   'AND => [
 *     0 => ['id',100,'='],
 *     1 => ['ts',2010-01-01,'>'],
 *     'OR' => [
 *       0 => ['user_id',5,'='],
 *       1 => ['user_id',10,'='],
 *     ]
 *   ],
 * ]
 *
 * 
 * @author ura ura@itx.ru
 * @version 1.0 2021-03-09
 */

namespace Cmatrix\Orm\Cql;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Orm as orm;

class Rules {
    
    private $Cond;
    private $Rules = [];
    
    // --- --- --- --- --- --- --- ---
    function __construct($cond='and'){
        $this->Cond = $cond;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Data' : return $this->Rules;
            case 'Query' : return $this->getMyQuery();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyQuery(){
        return array_map(function($key,$val){
            return array_map(function($val){
                return orm\Query\Phrase::get($val);
            },$val);
        },array_keys($this->Data),array_values($this->Data));
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function add(...$params){
        // --- массив условий
        if(count($params) == 1 && is_array($params[0])){
            array_map(function($rule){
                if(count($rule) < 2) throw new ex\Error('error cql rule "'.serialize($rule).'".');
                $this->add($rule[0],$rule[1],(isset($rule[2]) ? $rule[2] : '='));
            },$params[0]);
        }
        // --- instanceof Rules
        elseif(count($params) == 1 && $params[0] instanceof Rules){
            $this->Rules[$this->Cond][] = $params[0]->Data;
        }
        // --- одиночное условие
        elseif(count($params) == 2){
            $this->Rules[$this->Cond][] = [$params[0],$params[1],'='];
        }
        elseif(count($params) == 3){
            $this->Rules[$this->Cond][] = [$params[0],$params[1],$params[2]];
        }
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    static function get($cond='and'){
        return new self($cond);
    }
}
?>