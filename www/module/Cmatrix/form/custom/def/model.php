<?php

class MyModel extends \Cmatrix\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $this->Config = \Cmatrix\Kernel\Config::get();
        
        return [
            'app' => [
                'name'       => $this->Config->getValue('app/name'),
                'title'      => $this->Config->getValue('app/name'),
                'descriptor' => $this->Config->getValue('app/info'),
			    'author'     => $this->Config->getValue('app/author'),
			    'version'    => $this->Config->getValue('app/version'),
			    'period'     => $this->getMyPreiod(),
            ],
            'web' => [
                'home'    => $this->getMyUrl(),
                'session' => \Cmatrix\Web\Page::get('session')->Url,
                'browser' => \Cmatrix\Web\Page::get('browser')->Url,
                'admin'   => \Cmatrix\Web\Page::get('admin')->Url,
                'favicon' => \Cmatrix\Kernel\Ide\Resource::get('Cmatrix/icons/def.ico')->Wpath,
            ]
        ];
    }
    
    // --- --- --- --- --- --- --- ---
	protected function getMyPreiod(){
        $now = date('Y');
        $since = $this->Config->getValue('app/since');
        return $now == $since ? $now : $since .' - '. $now;
	}
	
    // --- --- --- --- --- --- --- ---
	protected function getMyUrl(){
		$url = $this->Config->getValue('app/url');
		$label = str_start($url,'http://') ? str_after($url,'http://') : $url;
		return '<a href="' .$url. '">' .$label. '</a>';
	}
	
}
?>