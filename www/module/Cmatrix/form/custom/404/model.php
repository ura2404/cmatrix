<?php

class MyModel extends \Cmatrix\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'page' => [
                'name' => '404'
            ]
        ];
    }
}
?>