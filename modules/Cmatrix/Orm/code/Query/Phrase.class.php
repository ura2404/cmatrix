<?php
/**
 * Class Cmatrix\Orm\Query\Phrase
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-19
 */

namespace Cmatrix\Orm\Query;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Phrase{
    private $Left;
    private $Right;
    private $Cond;
    
    // --- --- --- --- --- --- --- ---
    function __construct($left,$right,$cond='='){
        $this->Left = $left;
        $this->Right = $right;
        $this->Cond = $cond;
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
    private function getMyQuery(){
        return $this->Left .'-'. $this->Right .'-'. $this->Cond;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(...$params){
        if(count($params) == 1 && is_array($params[0])){
            if(count($params[0]) < 2) throw new ex\Error('error cql rule "'.serialize($params[0]).'".');
            return self::get($params[0][0],$params[0][1],(isset($params[0][3]) ? $params[0][3] : '='));
        }
        elseif(count($params) == 2){
            $Left = $params[0];
            $Right = $params[1];
            $Cond = '=';
        }
        elseif(count($params) == 3){
            $Left = $params[0];
            $Right = $params[1];
            $Cond = $params[2];
        }
        
        return new self($Left,$Right,$Cond);
    }
    
}
?>