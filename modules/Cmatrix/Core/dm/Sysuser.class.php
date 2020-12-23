<?php
namespace Cmatrix\Core\Dm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Sysuser extends Entity {
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct();
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function beforeCreate($ob){
        parent::beforeCreate($ob);
    }
    
    // --- --- --- --- --- --- --- ---
    public function afterCreate($ob){
        parent::afterCreate($ob);
    }

}
?>