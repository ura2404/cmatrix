<?php
/**
 * Class Cmatrix\Orm\Cql
 * 
 * Cmatrix query language (CQL)
 * 
 * @author ura ura@itx.ru
 * @version 1.0 2019-06-10
 * @version 1.1 2021-03-09
 */

namespace Cmatrix\Orm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Cql {
    private $Dm;
    
    private $Rules;
    
    /**
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
     */
    
    // --- --- --- --- --- --- --- ---
    function __construct($dm){
        $this->Dm = $dm;
        $this->Rules = Cql\Rules::get();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Query' : return $this->getMyQuery();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function ruleInteger(...$params){
        $this->Rules->add(...$params);
        return $this;
    }
    
/*    public function rule($prop,$value,$cond='='){
        $this->Rules->rule($prop,$value,$cond);
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    public function rules(array $rules,$cond='AND'){
        $this->Rules->rules($rules,$cond);
        return $this;
    }
  */  
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self($dm);
    }
    
    // --- --- --- --- --- --- --- ---
    static function select($dm){
        return new self($dm);
    }
    
}

?>