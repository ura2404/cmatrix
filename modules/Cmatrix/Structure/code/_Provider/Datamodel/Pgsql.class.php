<?php
/**
 * Class Cmatrix\Structure\Provider\Datamodel\Pgsql
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-29
 */
 
namespace Cmatrix\Structure\Provider\Datamodel;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Pgsql extends \Cmatrix\Structure\Provider\Datamodel{
    private $Model;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        //parent::__construct();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : throw parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function getPropType($prop){
        $Type = $prop['type'];
        
        if($Type === '::id::') return 'BIGINT';
        if($Type === '::ip::') return 'VARCHAR(45)'; // 15 - ipv4, 45 - ipv6
        elseif($Type === '::hid::') return 'VARCHAR(32)';
        elseif($Type === 'string'){
            $Length = $prop['length'];
            return 'VARCHAR' .($Length ? '('. $Length .')' : null);
        }
        else return strtoupper($Type);    
    }
    
    // --- --- --- --- --- --- --- ---
    public function getPropDefault($prop){
        $Def = $prop['default'];
            
        if($Def === '::hid::')           $Value = "DEFAULT md5(to_char(now(), 'DDDYYYYNNDDHH24MISSUS') || random())";
        elseif($Def === '::now::')       $Value = 'DEFAULT CURRENT_TIMESTAMP';
        elseif($Def === '::counter::')   $Value = "DEFAULT nextval('". $this->getSequenceName($prop) ."')";
        elseif(strStart($Def,'::next(')) $Value = "DEFAULT nextval('". $_next($Def) ."')";
        else $Value =  'DEFAULT '. kernel\Orm\Query\Value::get($Def)->Query;
        
        return $Value;
    }
}
?>