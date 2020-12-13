<?php

class MyModel extends \Cmatrix\Web\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $this->Config = \Cmatrix\Web\Kernel::get()->Config;
        
        return [
            'app' => [
                'name'    => $this->Config->getValue('app/name'),
                'title'   => 'Master',
                'author'  => $this->Config->getValue('app/author'),
                'version' => $this->Config->getValue('app/version'),
                'period'  => $this->getMyPreiod(),
                
            ],
            'web' => [
                'home'    => \Cmatrix\Web\Page::get()->Path,
                // ???? 'favicon' => \Cmatrix\Web\Resource::get('Cmatrix/Web/form/custom/master/res/favicon.ico')->Path,
            ],
            'page' => [
                'name' => 'Master',
            ]
        ];
    }
    
    // --- --- --- --- --- --- --- ---
	protected function getMyPreiod(){
        $now = date('Y');
        $since = $this->Config->getValue('app/since');
        return $now == $since ? $now : $since .' - '. $now;
	}
}
?>