<?php

class MyModel extends \Cmatrix\Web\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $this->Config = \Cmatrix\Kernel\Config::get('Cmatrix/Web/www/config.json');
        
        return [
            'page' => [
                'name' => \Cmatrix\Web\Ide\Page::get('Cmatrix/Web/custom/session')->Config->getValue('page/name'),
            ],
            'session' => $this->getMySession()
        ];
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMySession(){
        \Cmatrix\Core\Session::get();
    }
}
?>