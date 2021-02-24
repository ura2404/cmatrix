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

class Pgsql extends \Cmatrix\Structure\Provider implements iPgsql{
    
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
			$Arr[] = 'DROP SEQUENCE IF EXISTS '. $Name .' CASCADE;';
			$Arr[] = 'CREATE SEQUENCE '. $Name .';';
			return implode("\n", $Arr);
        },$model->getSequenceProps());
        
        return $Arr;
    }
    
    // --- --- --- --- --- --- --- ---
    public function sqlCreateTable(structure\iModel $model){
        $Parent = $model->Model->Parent;
        $TableName = $model->getTableName();
        
        $Arr = [];
        $Arr[] = 'DROP TABLE IF EXISTS '. $TableName .' CASCADE;';
        $Arr[] = 'CREATE TABLE '. $TableName .'(';
        $Arr[] = implode(",\n",array_map(function($prop) use($model){
            
            $Arr = [];
            
            $Arr[] = $model->getPropName($prop);
            $Arr[] = $this->getPropType($prop);
            $Arr[] = $this->getPropDefault($model,$prop);
            $Arr[] = $this->getPropNotNull($prop);
            
            //return implode(" ",$Arr);
            return implode(" ",array_filter($Arr,function($val){ return !!$val; }));
            
        },$model->Model->OwnProps));
        $Arr[] = $Parent ? ') INHERITS ('. $model->getParentTableName() .');' : ');';
        
        //$Arr[] = $this->getFields();
        
        //$Json = kernel\Ide\Datamodel::get($this->Datamodel->Url)->Json;
        //$ParentTablename = $Json['parent'] ? kernel\Structure\Datamodel::get($Json['parent'])->Tablename : null;
        //$Arr[] = $ParentTablename ?  ') INHERITS ('. $ParentTablename .');' : ');';
        
        return $Arr;
    }

    // --- --- --- --- --- --- --- ---    
    public function getPropType($prop){
        return parent::getPropType($prop);
    }
    
    // --- --- --- --- --- --- --- ---
    public function getPropDefault($model,$prop){
        $_def = function() use($model,$prop){
            if($prop['default'] === '::hid::')         return "md5(to_char(now(), 'DDDYYYYNNDDHH24MISSUS') || random())";
            elseif($prop['default'] === '::counter::') return "nextval('". $model->getPropSequenceName($prop) ."')";
            else return parent::getPropDefault($model,$prop);
        };
        
        $Default = $_def();
        return $Default ? 'DEFAULT '.$Default : null;
        
    }
    
    // --- --- --- --- --- --- --- ---
    public function getPropNotNull($prop){
        return $prop['nn'] === true ? 'NOT NULL' : null;
    }
    
    // --- --- --- --- --- --- --- ---
    public function sqlCreatePk(structure\iModel $model){
        $Props = $model->getPkProps();
        
        $TableName = $model->getTableName();
        $PkName = $TableName .'__pk__'. implode('_',$Props);
        //$PkName = md5($TableName .'__pk__'. implode('_',$Props));
        
        $Arr = [];
        $Arr[] = 'ALTER TABLE ' .$TableName. ' DROP CONSTRAINT IF EXISTS ' .$PkName. ' CASCADE;';
        $Arr[] = 'ALTER TABLE ' .$TableName. ' ADD CONSTRAINT ' .$PkName. ' PRIMARY KEY (' .implode(',',$Props). ');';
        
        return implode("\n",$Arr);
    }
    
    // --- --- --- --- --- --- --- ---
    public function sqlCreateUniques(structure\iModel $model){
        //dump($model->getUniques());
    }

    // --- --- --- --- --- --- --- ---
    public function sqlCreateIndexes(structure\iModel $model){
        $TableName = $model->getTableName();
        
        return array_map(function($group) use ($TableName) {
            $Props = array_map(function($prop){
                return $prop['code'];
            },$group);
            
            $IndexName = $TableName .'__index__'. implode('_',$Props);
            //$IndexName = md5($TableName .'__index__'. implode('_',$Props));
            
            $Arr = [];
            $Arr[] = 'DROP INDEX IF EXISTS ' .$IndexName. ' CASCADE;';
            $Arr[] = 'CREATE INDEX ' .$IndexName. ' ON ' .$TableName. ' USING btree (' .implode(',',$Props). ');';
            
            return implode("\n",$Arr);
            
        },$model->Model->Indexes);
    }
    
    // --- --- --- --- --- --- --- ---
    public function sqlCreateGrant(structure\iModel $model){
        $TableName = $model->getTableName();
        
        $Arr = [];
        $Arr[] = 'GRANT SELECT ON '. $TableName .' TO PUBLIC;';
        $Arr[] = 'GRANT REFERENCES ON '. $TableName .' TO PUBLIC;';
        
        return $Arr;
    }
    
    // --- --- --- --- --- --- --- ---
    public function sqlCreateInit(structure\iModel $model){
        $TableName = $model->getTableName();
        $Init = $model->Model->Init;
        
        return array_map(function($init) use($model,$TableName){
            $Fields = array_keys($init);
            $Values = array_values($init);
            
			$Fields[] = 'session_id';
			$Values[] = '1';
			
			// проверить поля
			array_map(function($prop) use($model){
			    $model->Model->getProp($prop);
			},$Fields);
			
			$Values = array_map(function($val){ return $this->sqlValue($val); },$Values);
            
            return 'INSERT INTO ' .$TableName. ' (' .implode(',',$Fields). ') VALUES (' .implode(',',$Values). ');';
        },$model->Model->Init);
    }
    
    // --- --- --- --- --- --- --- ---
    public function sqlValue($val,$cond='='){
        //--- --- --- --- --- --- --- ---
        $_quote = function($val){
            return "'" .str_replace("'","''",$val). "'";
        };
        
        //--- --- --- --- --- --- --- ---
        if(is_array($val)){
            $Arr = array_map(function($val) use($_quote){
                return $this->sqlValue($val);
            },$val);
            return '('. implode(',',$Arr) .')';
        }
        elseif($val === true) return 'TRUE';
        elseif($val === null) return 'NULL';
        elseif($val === false) return 'FALSE';
        elseif(gettype($val) === 'string'){
            if(strStart($val,'raw::')) return strAfter($val,'raw::');
            elseif($val === 'true' || $val === 'null' || $val === 'false') return strtoupper($val);
            elseif(strStart($val,'(') && strEnd($val,')')) return $val;
            elseif(strtolower($val) === '::now::') return 'CURRENT_TIMESTAMP';
            else{
                    if($cond == '%'   || $cond == '!%'  ) return $_quote('%'.cmStrToLower($val));
                elseif($cond == '%%'  || $cond == '!%%' ) return $_quote('%'.cmStrToLower($val).'%');
                elseif($cond == '%%%' || $cond == '!%%%') return $_quote(cmStrToLower($val).'%');
                else return $_quote($val);
            }
        }
        else return $val;
    }

}
?>