<?php
class MyModel extends \Cmatrix\Web\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        //$this->Config = \Cmatrix\Web\Kernel::get()->Config;
        
        return [
            'page' => [
                'name' => \Cmatrix\Web\Ide\Page::get('Cmatrix/Web/custom/session')->Config->getValue('page/name'),
            ],
            'session' => $this->getMySession()
        ];
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMySession(){
        dump(\Cmatrix\Core\Session::get());
    }
}
?>