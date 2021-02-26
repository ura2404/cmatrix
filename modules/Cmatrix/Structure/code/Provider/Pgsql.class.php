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
            
            return implode(" ",array_filter($Arr,function($val){ return !!$val; }));
        },$model->Model->OwnProps));
        $Arr[] = $Parent ? ') INHERITS ('. $model->getParentTableName() .');' : ');';

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
        $TableName = $model->getTableName();
        $PkName    = $model->getPkName();
        $PkProps   = $model->getPkProps();
        
        $Arr = [];
        $Arr[] = 'ALTER TABLE ' .$TableName. ' DROP CONSTRAINT IF EXISTS ' .$PkName. ' CASCADE;';
        $Arr[] = 'ALTER TABLE ' .$TableName. ' ADD CONSTRAINT ' .$PkName. ' PRIMARY KEY (' .$PkProps. ');';
        
        return implode("\n",$Arr);
    }
    
    // --- --- --- --- --- --- --- ---
    public function sqlCreateUniques(structure\iModel $model){
        //dump($model->getUniques());
    }

    // --- --- --- --- --- --- --- ---
    public function sqlCreateIndexes(structure\iModel $model){
        $Arr = array_map(function($group) use($model){
            $TableName  = $model->getTableName();
            $IndexProps = $model->getIndexProps($group);
            $IndexName  = $model->getIndexName($group);
            
            $Arr = [];
            $Arr[] = 'DROP INDEX IF EXISTS ' .$IndexName. ' CASCADE;';
            $Arr[] = 'CREATE INDEX ' .$IndexName. ' ON ' .$TableName. ' USING btree (' .$IndexProps. ');';
            
            return implode("\n",$Arr);
        },$model->Model->Indexes);
        
        return implode("\n",$Arr);
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
        return array_map(function($init) use($model){
            foreach($init as $key=>$val) if($key{0} === '_') unset($init[$key]);
            
            $Fields = array_keys($init);
            $Values = array_values($init);
            //dump($Fields);
            
            $Values = array_map(function($prop,$index) use($model,$Values){
                $Prop = $model->Model->getProp($prop);
                return $this->sqlValue($Prop,$Values[$index]);
            },$Fields,array_keys($Fields));
            //dump($Values);die();
            
            return 'INSERT INTO ' .$model->getTableName(). ' (' .implode(',',$Fields). ') VALUES (' .implode(',',$Values). ');';
        },$model->Model->Init);
    }

}
?>