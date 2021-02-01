<?php
/**
 * Class \Cmatrix\Structure\Model
 * 
 * @author ura@itx.ru 
 * @version 1.0 2021-02-01
 */
 
namespace Cmatrix\Structure;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

abstract class Model implements \Cmatrix\Structure\iModel {
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    abstract public function getSequenceProps();
    abstract public function getPropSequenceName($prop);
    abstract public function getTableName();
    abstract public function getPropName($prop);

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(kernel\Ide\iModel $model){
        $_target = function() use($model){
            if($model instanceof kernel\Ide\iDatamodel) return 'datamodel';
            elseif($model instanceof kernel\Ide\iDatasource) return 'datasource';
            else throw new ex\Error('model "' .get_class($model). '" is not valid.');
        };
        
        $ClassName = '\Cmatrix\Structure\Model\\' .ucfirst($_target());
        return new $ClassName($model);
        
    }
}
?>