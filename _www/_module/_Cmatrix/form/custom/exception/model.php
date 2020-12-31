<?php

class MyModel extends \Cmatrix\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        header('HTTP/1.1 500 An exception was thrown.',true,500);
        
        return [
            'page' => [
                'name' => 'Error',
                'favicon' => \Cmatrix\Kernel\Ide\Resource::get('Cmatrix/icons/404.ico')->Wpath,
            ],
            'error' => [
                'message' => \Cmatrix\Web\Exception::$MESSAGE,
                'trace' =>  \Cmatrix\Web\Exception::$TRACE,
            ]
        ];
    }

}
?>