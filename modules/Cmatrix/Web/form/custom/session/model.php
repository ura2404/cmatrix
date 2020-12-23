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
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function getMySession(){
        $Enabled = ['id','create_ts'];
        //$Arr = [];
        $Props = \Cmatrix\Core\Session::get()->Dm->Props;
        $Session = \Cmatrix\Core\Session::get()->Values;
        dump($Session);
        dump($Props);
        
        //$Arr['id'] = $Session['id'];
        
        
        //dump(\Cmatrix\Core\Session::get()->getValues());
        //dump(\Cmatrix\Core\Session::get());
    }
}
?>