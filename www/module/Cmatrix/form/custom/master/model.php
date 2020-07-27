<?php

class MyModel extends \Cmatrix\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $this->Config = \Cmatrix\Kernel\Config::get();
        
        return [
            'app' => [
                'name'    => $this->Config->getValue('app/name'),
			    'author'  => $this->Config->getValue('app/author'),
			    'version' => $this->Config->getValue('app/version'),
			    'period'  => $this->getMyPreiod(),
                
            ],
            'web' => [
                'favicon' => \Cmatrix\Kernel\Ide\Resource::get('Cmatrix/icons/def.ico')->Wpath,
                'home' => \Cmatrix\Web\Page::get()->Url
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