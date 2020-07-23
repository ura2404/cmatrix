<?php
/**
 * Class View
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Mvc;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class View {
    protected $Url;

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Data' : return $this->getMyData();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyData(){
        return;
    }
}

?>