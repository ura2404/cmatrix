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
    protected function inheritIndex(array $props){
        //dump($props);
        
        // кол-во свойств
        $N = count($props);
        
        // перебор возможных вариантов
        $Ret = [];
        for($i=1; $i<pow(2,$N); $i++){
            // 1. получить бинарную строку
            //   например: 0010110
            //   здесь форматирование строки для дополнения лидирующих нулей: %'.05s
            //     - .0 - дополнить нулями
            //     - 5  - общая длина 5 символов
            //     - s  - вовод строчных символов
            $S = str_split(sprintf("%'.0".$N."s",decbin($i)));
            
            // 2. получить все варианты и пометить неиспользуемые варианты (nn=true)
            $Variants = array_map(function($val,$ind) use($props){
                if($val === '0' && $props[$ind]['nn']) return -1;
                return $val;
            },$S,array_keys($S));
            
            // 3. удалить неиспользуемые варианты
            $Fl = array_reduce($Variants,function($carry, $item){
                $carry = $carry && ($item == -1 ? false : true);
                return $carry;
            },true);
            if(!$Fl) continue;
            
            // 3. перебрать варианты для условий
            // если символ '0' - условие is null
            // если символ '1' - условие is not null
            //dump($Variants);
            
            $Props = [];
            $Rules = [];
            $Arr = array_map(function($var,$index) use($props,&$Props,&$Rules){
                if($props[$index]['nn'] || (!$props[$index]['nn'] && $var == '1')) $Props[] = $props[$index]['code'];
                
                if(!$props[$index]['nn'] && $var == '0') $Rules[] = $props[$index]['code'] .' IS NULL';
                if(!$props[$index]['nn'] && $var == '1') $Rules[] = $props[$index]['code'] .' IS NOT NULL';
            },$Variants,array_keys($Variants));
            
            $Ret[] = [
                'props' => $Props,
                'rules' => $Rules
            ];
        }
        //dump($Ret);
        return $Ret;
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @retrun string - трансформированное имя
     */
    private function getTransName($name){
        // 1.
        return $name;
        
        // 2.
        //return 'cm'.md5($name);
        
        // 3.
        //$Prefix = \Cmatrix\Db\Kernel::get()->CurConfig->getValue('prefix',null);
        //$Prefix = $Prefix ? $Prefix : 'cm';
        //return $Prefix .'_'. md5($name);
    }
    
    // --- --- --- --- --- --- --- ---
    private function getTableName(kernel\Ide\iModel $model){
        $Prefix = \Cmatrix\Db\Kernel::get()->CurConfig->getValue('prefix',null);
        $Name = $Prefix.str_replace('/','_',$model->Json['code']);
        
        //return $this->getTransName($Name);
        return $Name;
    }
    
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function sqlValue($prop,$val,$cond='='){
        switch($prop['type']){
            case 'timestamp' :
                return "'" .parent::sqlValue($prop,$val,$cond). "'";
            default :
                return parent::sqlValue($prop,$val,$cond);
        }
    }
    
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
    public function sqlCreateFk(structure\iModel $model){
        $TableName  = $model->getTableName();        
        
        $Arr = array_map(function($prop) use($TableName,$model){
            $_to = function() use($prop){
                $EntityUrl = $prop['association']['entity'];
                $PropName = $prop['association']['prop'];
                $Datamodel = kernel\Ide\Datamodel::get($EntityUrl);
                $Tablename = $this->getTableName($Datamodel);
                $PropName = $Datamodel->getProp($PropName)['code'];
                return $Tablename .'('. $PropName .')';
            };
            
            $FkName = $TableName .'__fk__'. $this->getTransName($prop['code']);
            
            $Arr = [];
            $Arr[] = 'ALTER TABLE ' .$TableName. ' DROP CONSTRAINT IF EXISTS ' .$FkName. ' CASCADE;';
            $Arr[] = 'ALTER TABLE ' .$TableName. ' ADD CONSTRAINT ' .$FkName. ' FOREIGN KEY ('. $prop['code'] .') REFERENCES ' .$_to(). ';';
            
            return implode("\n",$Arr);
            
        },$model->Model->Association);
        
        return implode("\n",$Arr);
    }
    
    // --- --- --- --- --- --- --- ---
    public function sqlCreateUniques(structure\iModel $model){
        return $this->sqlCreateIndexes($model,true);
    }

    // --- --- --- --- --- --- --- ---
    public function sqlCreateIndexes(structure\iModel $model,$isUnique=false){
        $TableName  = $model->getTableName();
        
        $_src = function() use($model,$isUnique){
            if($isUnique) return $model->Model->Uniques;
            
            $Indexes = $model->Model->Indexes;
            $Association = $model->Model->Association;
            if(count($Association)) $Indexes[] = $Association;
            
            return $Indexes;
        };
        
        $Arr = array_map(function($group) use($model,$isUnique,$TableName){
            $Arr = array_map(function($val) use($model,$isUnique,$TableName){
                $Props = $val['props'];
                $Rules = $val['rules'];
                
                if($isUnique && $model->isActiveProp) $Rules[] = 'active IS NOT NULL';
                
                $IndexName = $TableName .'__' .($isUnique ? 'uniq' : 'index'). '__'. $this->getTransName(implode('_',$Props));
                $IndexProps = implode(',',$Props);
                
                $Arr = [];
                $Arr[] = 'DROP INDEX IF EXISTS ' .$IndexName. ' CASCADE;';
                $Arr[] = 'CREATE INDEX ' .$IndexName. ' ON ' .$TableName. ' USING btree (' .$IndexProps. ')' .(count($Rules) ? ' WHERE '.implode(' AND ',$Rules) : null). ';';
                
                //так не работает
                //$Arr[] = 'ALTER TABLE ' .$TableName. ' DROP CONSTRAINT IF EXISTS ' .$IndexName. ' CASCADE;';
                //$Arr[] = 'ALTER TABLE ' .$TableName. ' ADD CONSTRAINT ' .$IndexName. ' UNIQUE (' .$IndexProps. ')' .(count($Rules) ? ' WHERE '.implode(' AND ',$Rules) : null). ';';
                return implode("\n",$Arr);
                
            },$this->inheritIndex($group));
            
            return implode("\n",$Arr);
            
        },$_src());
        
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