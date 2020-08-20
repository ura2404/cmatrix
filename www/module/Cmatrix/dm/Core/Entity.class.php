<?php
/**
 * Class Session
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-18
 */
 
namespace Cmatrix\Datamodel\Core;
use \Cmatrix as cm;
use \Cmatrix\Orm as orm;

class Entity extends orm\Datamodel{
    
    // --- --- --- --- --- --- --- ---
    function __construct($id=null){
        parent::__construct($id);
    }
    
    // --- --- --- --- --- --- ---
    protected function getMyName(){
        return 'Сущность';
    }
    
    // --- --- --- --- --- --- --- ---
    /*protected function createJson(){
        return [
            'code' => 'Cmatrix/Core/Entity',
            'props' => [
                'id' => [],
                'parent_id' => [],
                'chain_id' => [],
                'status' => [],
                'active' => [],
                'hidden' => [],
                'deleted' => [],
                'info' => [],
            ]
        ];
    }*/
    
}
?>