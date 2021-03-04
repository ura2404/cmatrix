<?php
/**
 * Class \Cmatrix\Structure\Model
 * 
 * Реализует механизм создания управляющих скриптов для датамодели и провайдера
 *
 * @author ura@itx.ru 
 * @version 1.0 2021-02-01
 */
 
namespace Cmatrix\Structure\Model;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Structure as structure;
use \Cmatrix\Kernel\Exception as ex;

class Datamodel extends \Cmatrix\Structure\Model implements iDatamodel{
    
    // --- --- --- --- --- --- --- ---
    function __construct(kernel\Ide\iDatamodel $model){
        $this->Model = $model;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Model' : return $this->Model;
            case 'isActiveProp' : return $this->getMyIsActiveProp();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @retrun bool - есить ли поле active
     */
    protected function getMyIsActiveProp(){
        return $this->Model->isPropExists('active');
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function getSqlCreate(structure\iProvider $provider){
        $Queries = [];
        
        //$this->Model->Parent ? $Queries['parent'] = (new self($this->Model->Parent))->getSqlCreate($provider) : null;
        
        //$Queries['main'][] = '-- -------------------------------------------------------------------';
        //$Queries['main'][] = '-- --- dm::' .$this->Model->Url. '---------------------------';
        //$Queries['main'][] = "";
        
        $Queries['main'][] = '-- --- sequence --- dm::' .$this->Model->Url. ' -------------';
        $Queries['main'][] = $provider->sqlCreateSequence($this);
        $Queries['main'][] = "";
        
        $Queries['main'][] = '-- --- table --- dm::' .$this->Model->Url. ' ----------------';
        $Queries['main'][] = $provider->sqlCreateTable($this);
        $Queries['main'][] = "";

        $Queries['main'][] = '-- --- pk --- dm::' .$this->Model->Url. ' -------------------';
        $Queries['main'][] = $provider->sqlCreatePk($this);
        $Queries['main'][] = "";

        $Queries['main'][] = '-- --- uniques --- dm::' .$this->Model->Url. ' --------------';
		$Queries['main'][] = $provider->sqlCreateUniques($this);
		$Queries['main'][] = "";
        
        $Queries['main'][] = '-- --- indexes --- dm::' .$this->Model->Url. ' --------------';
		$Queries['main'][] = $provider->sqlCreateIndexes($this);
		$Queries['main'][] = "";
		
        $Queries['main'][] = '-- --- grant --- dm::' .$this->Model->Url. ' ----------------';
		$Queries['main'][] = $provider->sqlCreateGrant($this);
		$Queries['main'][] = "";
		
        $Queries['init'][] = '-- --- init --- dm::' .$this->Model->Url. ' -----------------';
		$Queries['init'][] = $provider->sqlCreateInit($this);
		$Queries['init'][] = "";
		
        $Queries['fk'][] = '-- --- fk --- dm::' .$this->Model->Url. ' -------------------';
		$Queries['fk'][] = $provider->sqlCreateFk($this);
		$Queries['fk'][] = "";
        
        //dump($Queries);
        return $Queries;
        
        return implode("\n",array2line($Queries))."\n";
        //return implode("\n",array2line($Queries));
        //return implode("\n", $Queries);
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @retrun string - трансформированное имя
     */
    public function getTransName($name){
        // 1.
        return 'cm'.md5($name);
        
        // 2.
        //$Prefix = \Cmatrix\Db\Kernel::get()->CurConfig->getValue('prefix',null);
        //$Prefix = $Prefix ? $Prefix : 'cm';
        //return $Prefix .'_'. md5($name);
        
        // 3.
        //return $name;
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return array - массив свойств для счётчика
     */
    public function getSequenceProps(){
        return array_filter($this->Model->Props,function($prop){ return $prop['default'] === '::counter::'; });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return array - массив свойств для primary key
     */
    public function getPkProps(){
        $Props = array_filter($this->Model->OwnProps,function($prop){ return !!$prop['pk']; });
        $Arr = array_map(function($prop){ return $prop['code']; },$Props);
        return implode(',',$Arr);
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имя primary key
     */
    public function getPkName(){
        $Name = $this->getTableName() .'__pk__'. str_replace(',','_',$this->getPkProps());
        return $this->getTransName($Name);
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имя счётчика для свойства
     */
    public function getPropSequenceName($prop){
        $Name = $this->getTableName() . '__seq__' . $prop['code'];
        $Name = strtolower($Name);
        return $this->getTransName($Name);
    }
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имя таблицы
     */
    public function getTableName(){
        $Prefix = \Cmatrix\Db\Kernel::get()->CurConfig->getValue('prefix',null);
        $Name = $Prefix.str_replace('/','_',$this->Model->Json['code']);
        return $Name;
        //return $this->getTransName($Name);
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     *@return string - имя таблицы родителя
     */
    public function getParentTableName(){
        $Parent = $this->Model->Parent;
        $Name = $Parent ? (new self($this->Model->Parent))->getTableName() : null;
        return $Name;
        //return $Name ? $this->getTransName($Name) : null;
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имя свойства
     */
    public function getPropName($prop){
        $Name = $prop['code'];
        return $Name;
        //return $this->getTransName($Name);
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имена свойств для index через запятую
     */
    public function getIndexProps(array $props){
        $Arr = array_map(function($prop){ return $prop['code']; },$props);
        return implode(',',$Arr);
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имя индекса для массива полей
     */
    public function getIndexName(array $props){
        $Name = $this->getTableName() .'__index__'. str_replace(',','_',$this->getIndexProps($props));
        return $this->getTransName($Name);
    }
    
    /*
    private function getMySql(){
        $Queries = [];
        
        $Queries['main'][] = '-- --- dm::' .$this->Model->Url. '-----------------------';
        $Queries['main'][] = '-- sequence --- dm::' .$this->Model->Url. ' -------------';
		$Queries['main'][] = $this->getSequences();
		$Queries['main'][] = "";
		
        $Queries['main'][] = '-- table --- dm::' .$this->Model->Url. ' ----------------';
		$Queries['main'][] = $this->getTable();
		$Queries['main'][] = "";
		
        $Queries['main'][] = '-- uniques --- dm::' .$this->Model->Url. ' --------------';
		//$Queries['main'][] = $this->getUniques();
		$Queries['main'][] = "";
		
        $Queries['main'][] = '-- indexes --- dm::' .$this->Model->Url. ' --------------';
		//$Queries['main'][] = $this->getIndexes();
		$Queries['main'][] = "";
		
        $Queries['main'][] = '-- grant --- dm::' .$this->Model->Url. ' ----------------';
		//$Queries['main'][] = $this->getGrant();
		$Queries['main'][] = "";
		
        $Queries['init'][] = '-- init --- dm::' .$this->Model->Url. ' -----------------';
		//$Queries['init'][] = $this->getInit();
		$Queries['init'][] = "";
		
        $Queries['fk'][]   = '-- fk --- dm::' .$this->Model->Url. ' -------------------';
		//$Queries['fk'][]   = $this->getFk();
		$Queries['fk'][]   = "";
		
		//dump($Queries);
		
		return implode("\n",array2line($Queries))."\n";
    }
    
    // --- --- --- --- --- --- --- ---
    private function getSequences(){
        $Props = array_filter($this->Model->Props,function($prop){ return $prop['default'] === '::counter::'; });
        $Arr = array_map(function($prop){
            $Arr = [];
			$Name = $this->getPropSequenceName($prop);
			$Arr[] = 'DROP SEQUENCE IF EXISTS '. $Name .';';
			$Arr[] = 'CREATE SEQUENCE '. $Name .';';
			return implode("\n", $Arr);
        },$Props);
        return $Arr;
        return implode("\n", $Arr);
    }
    
    // --- --- --- --- --- --- ---
    private function getPropSequenceName($prop){
        $name = $this->Tablename .'_'. $prop['code'] .'_seq';
        $name = strtolower($name);
        return $name;
    }

    // --- --- --- --- --- --- ---
    private function getMyTablename($code=null){
        $Code = $code ? $code : $this->Model->Json['code'];
        
        $Prefix = \Cmatrix\Db\Kernel::get()->CurConfig->getValue('prefix',null);
        return $Prefix.str_replace('/','_',$Code);
    }

    // --- --- --- --- --- --- ---
    private function getTable(){
        $Parent = $this->Model->Parent;
        
        $Arr = [];
        $Arr[] = 'DROP TABLE IF EXISTS '. $this->Tablename .' CASCADE;';
        $Arr[] = 'CREATE TABLE '. $this->Tablename .'(';
        $Arr[] = implode("\n",array_map(function($prop){ return $this->getPropSql($prop); },$this->Model->Props));
        $Arr[] = $Parent ? ') INHERITS ('. $this->getMyTablename($Parent->Json['code']) .');' : ');';
        
        //$Arr[] = $this->getFields();
        
        //$Json = kernel\Ide\Datamodel::get($this->Datamodel->Url)->Json;
        //$ParentTablename = $Json['parent'] ? kernel\Structure\Datamodel::get($Json['parent'])->Tablename : null;
        //$Arr[] = $ParentTablename ?  ') INHERITS ('. $ParentTablename .');' : ');';
        
        return implode("\n", $Arr);
    }
    
    // --- --- --- --- --- --- ---
    private function getPropSql($prop){
        $Arr = [];
        $Arr[] = $prop['code'];
        $Arr[] = $this->Provider->getPropType($prop);
        if($prop['default'] !== null) $Arr[] = $this->Provider->getPropDefault($prop);
        
        return implode(" ", $Arr);
    }
    
    
    // --- --- --- --- --- --- ---
    function getFields(){
        $_type = function($prop){
            $Type = $prop['type'];
            
            if($Type === '::id::') return 'BIGINT';
            if($Type === '::ip::') return 'VARCHAR(45)'; // 15 - ipv4, 45 - ipv6
            elseif($Type === '::hid::') return 'VARCHAR(32)';
            elseif($Type === 'string'){
                $Length = $prop['length'];
                return 'VARCHAR' .($Length ? '('. $Length .')' : null);
            }
            else return strtoupper($Type);    
        };
        
        $_next = function($def){
            $Url = strBetween($def,"(",',');
            $FieldName = strBetween($def,",",')');
            
            $Datamodel = kernel\Datamodel::get($Url);
            $Prop = $Datamodel->getProp($FieldName);
            
            $Pgsql = new Pgsql($Datamodel);
            return $Pgsql->getSequenceName($Prop);
        };
        
        $_default = function($prop) use($_next){
            $Def = $prop['default'];
            
            if($Def === '::hid::')           $Value = "DEFAULT md5(to_char(now(), 'DDDYYYYNNDDHH24MISSUS') || random())";
            elseif($Def === '::now::')       $Value = 'DEFAULT CURRENT_TIMESTAMP';
            elseif($Def === '::counter::')   $Value = "DEFAULT nextval('". $this->getSequenceName($prop) ."')";
            elseif(strStart($Def,'::next(')) $Value = "DEFAULT nextval('". $_next($Def) ."')";
            else $Value =  'DEFAULT '. kernel\Orm\Query\Value::get($Def)->Query;
            
            return $Value;
        };
        
        // --- --- --- --- --- ---        
        $Arr = [];
        $Props = $this->Datamodel->Props;
		foreach($Props as $code=>$prop){
		    $Field = [];
		    $Field[] = $prop['code'];
		    $Field[] = $_type($prop);
		    if($prop['default'] !== null) $Field[] = $_default($prop);
		    if($prop['nn'] === true) $Field[] = 'NOT NULL';
		    
		    $Arr[] = implode(' ',$Field);
		}        
		
		$Arr[] = 'PRIMARY KEY (id)';
		
        return "\t".implode(",\n\t",$Arr);
    }
    
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(kernel\Ide\iModel $model, structure\iProvider $provider){
        return new self($model,$provider);
    }
    */
}
?>