<?php
namespace Cmatrix\Core\Dm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Orm as orm;
use \Cmatrix\Core as core;

class Entity extends orm\Datamodel {
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct();
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function beforeCreate($ob){
    }
    
    // --- --- --- --- --- --- --- ---
    public function afterCreate($ob){
    }

}
?>