<?php
/**
 * Class Session
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-14
 */

namespace Cmatrix\Datamodel\Core;
use \Cmatrix as cm;

class Session extends Entity{
    
    // --- --- --- --- --- --- --- ---
    function __construct($id=null){
        parent::__construct($id);
        
        //dump(cm\Kernel\Ide\Datamodel::get('Cmatrix/Core/Session')->Path);
    }
    
    // --- --- --- --- --- --- ---
    protected function getMyName(){
        return 'Сессия';
    }
    
    // --- --- --- --- --- --- --- ---
    /*protected function createJson(){
        dump('session create json');
        
        return [
            'code' => $this->getMyUrl(),
            'name' => $this->getMyName(),
            'parent' => $this->getMyParentUrl(),
            'props' => [
                'ip' => [],
            ]
        ];
        
    }*/
}
?>