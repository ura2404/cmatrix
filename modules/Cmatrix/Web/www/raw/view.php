<?php

class View{

    // --- --- --- --- ---- ---
    function __get($name){
        switch($name){
            case 'Template' :
                $path = realpath(dirname(__FILE__).'/template.html');
                return file_get_contents($path);
        }
    }
    
    // --- --- --- --- ---- ---
    static function get(){
        return new View();
    }

}

?>