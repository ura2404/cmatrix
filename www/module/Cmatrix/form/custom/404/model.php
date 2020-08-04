<?php

class MyModel extends \Cmatrix\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'page' => [
                'name' => '404'
            ],
            'pic' => [
                '404' => \Cmatrix\Kernel\Ide\Resource::get('Cmatrix/custom/404/404-1.jpg')->Wpath
            ]
        ];
    }
}
?>