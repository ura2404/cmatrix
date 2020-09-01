<?php

class MyModel extends \Cmatrix\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $Session = \Cmatrix\Core\Session::get();
        //dump($Session,'Session');
        dump($Session->Instance,'Instance');
        //dump($Session->Instance->Json,'Json');
        //dump($Session->Instance->Props,'Props');
        //dump($Session->Instance->OwnProps,'OwnProps');
        //dump($Session->Instance->id,'Instance id');
        //dump($Session->Instance->ip4,'Instance ip4');
        
        return [
            'page' => [
                'name' => 'Session',
            ],
            //'id' => $Session->Instance->id,
            
            //'ip' => $Session->Instance->ip
        ];
    }
}
?>