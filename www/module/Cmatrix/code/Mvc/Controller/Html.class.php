<?php
/**
 * Class Html
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-27
 */

namespace Cmatrix\Mvc\Controller;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Html extends \Cmatrix\Mvc\Controller {
    // --- --- --- --- --- --- --- ---
    function __construct($view, $model){
        parent::__construct($view, $model);
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyData(){
        $_parser = function($view,array $model){
            $arr = explode('{{',$view);
            
            foreach($arr as $key=>$value){
                if(strpos($value,'}}')!=false){
                    $k = trim(str_before($value,'}}'));
                    $v = arrayGetValue(explode('.',$k),$model);
                    $arr[$key] = $v. str_after($value,'}}');
                }
            }
            return implode('',$arr);
        };
        
        $DataView = $this->View->Data;
        $DataModel = $this->Model->Data;
        
        return $_parser($DataView,$DataModel);
    }
}
?>