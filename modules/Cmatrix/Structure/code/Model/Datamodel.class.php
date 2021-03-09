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
        //$Prop = array_map(function($prop){ return $prop['code']; },$Props);
        return $Props;
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имена свойств для index через запятую
     */
    public function getIndexProps(array $props){
        $Arr = array_map(function($prop){ return $prop['code']; },$props);
        return implode(',',$Arr);
    }
}
?>