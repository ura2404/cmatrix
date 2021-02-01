<?php
/**
 * Class Cmatrix\Structure\Provider\Pgsql
 *
 * @author ura@itx.ru 
 * @version 1.0 2021-02-01
 */
 
namespace Cmatrix\Structure\Provider;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Structure as structure;

class Pgsql extends \Cmatrix\Structure\Provider {
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
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
    public function sqlCreateSequence(structure\iModel $model){
        $Arr = array_map(function($prop) use($model){
            $Arr = [];
			$Name = $model->getPropSequenceName($prop);
			$Arr[] = 'DROP SEQUENCE IF EXISTS '. $Name .';';
			$Arr[] = 'CREATE SEQUENCE '. $Name .';';
			return implode("\n", $Arr);
        },$model->getSequenceProps());
        return implode("\n", $Arr);
    }
    
    // --- --- --- --- --- --- --- ---
    public function sqlCreateTable(structure\iModel $model){
        $Parent = $model->Model->Parent;
        $TableName = $model->getTableName();
        
        $Arr = [];
        $Arr[] = 'DROP TABLE IF EXISTS '. $TableName .' CASCADE;';
        $Arr[] = 'CREATE TABLE '. $TableName .'(';
        $Arr[] = implode(",\n",array_map(function($prop) use($model){
            return $this->sqlCreateProp($model,$prop);
        },$model->Model->OwnProps));
        $Arr[] = $Parent ? ') INHERITS ('. structure\Model::get($Parent)->getTableName() .');' : ');';
        
        //$Arr[] = $this->getFields();
        
        //$Json = kernel\Ide\Datamodel::get($this->Datamodel->Url)->Json;
        //$ParentTablename = $Json['parent'] ? kernel\Structure\Datamodel::get($Json['parent'])->Tablename : null;
        //$Arr[] = $ParentTablename ?  ') INHERITS ('. $ParentTablename .');' : ');';
        
        return implode("\n", $Arr);
    }

    // --- --- --- --- --- --- --- ---
    public function sqlCreateProp(structure\iModel $model, array $prop){
        $Arr = [];
        $Arr[] = $model->getPropName($prop);
        $Arr[] = $this->getPropType($prop);
        if($prop['default'] !== null) $Arr[] = $this->getPropDefault($model,$prop);
        return implode(" ",$Arr);
    }

    // --- --- --- --- --- --- --- ---
    public function sqlCreateUniques(structure\iModel $model){
        //dump($model->getUniques());
    }
    
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
    public function getPropDefault($model,$prop){
        $Def = $prop['default'];
            
        if($Def === '::hid::')           $Value = "DEFAULT md5(to_char(now(), 'DDDYYYYNNDDHH24MISSUS') || random())";
        elseif($Def === '::now::')       $Value = 'DEFAULT CURRENT_TIMESTAMP';
        elseif($Def === '::counter::')   $Value = "DEFAULT nextval('". $model->getPropSequenceName($prop) ."')";
        //elseif(strStart($Def,'::next(')) $Value = "DEFAULT nextval('". $_next($Def) ."')";
        //else $Value =  'DEFAULT '. kernel\Orm\Query\Value::get($Def)->Query;
        
        return $Value;
    }
}
?>