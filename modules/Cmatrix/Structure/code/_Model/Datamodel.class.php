<?php
/**
 * Class \Cmatrix\Structure\Model\Datamodel
 * 
 * Реализует механизм создания управляющих скриптов для датамодели и провайдера
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-29
 */
 
namespace Cmatrix\Structure\Model;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Structure as structure;
use \Cmatrix\Kernel\Exception as ex;

class Datamodel {
    
    protected $Model;
    protected $Provider;
    
    // --- --- --- --- --- --- --- ---
    function __construct(kernel\Ide\iModel $model, structure\iProvider $provider){
        $this->Model = $model;
        $this->Provider = $provider;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Sql' : return $this->getMySql();
            case 'Tablename' : return $this->getMyTablename();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
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
}
?>
