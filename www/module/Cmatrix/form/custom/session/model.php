<?php

class MyModel extends \Cmatrix\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $Session = \Cmatrix\Core\Session::get();
        dump($Session->Instance,'Instance');

        return [
            'page' => [
                'name' => 'Session',
            ],
        ];
    }
}
?>