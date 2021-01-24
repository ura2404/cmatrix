<?php
class MyModel extends \Cmatrix\Web\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $this->Config = \Cmatrix\App\Kernel::get()->Config;
        
        return [
            'app' => [
                'name'       => $this->Config->getValue('app/name'),
                'title'      => $this->Config->getValue('app/name'),
                'descriptor' => $this->getMyDescriptor(),
                'author'     => $this->Config->getValue('app/author'),
                'version'    => $this->Config->getValue('app/version'),
                'period'     => $this->getMyPreiod(),
            ],
            'web' => [
                'home'    => $this->getMyUrl(),
                'session' => \Cmatrix\Web\Page::get('session')->Path,
                'browser' => \Cmatrix\Web\Page::get('browser')->Path,
                'admin'   => \Cmatrix\Web\Page::get('admin')->Path,
                'check'   => \Cmatrix\Web\Page::get('check')->Path,
                'favicon' => \Cmatrix\Web\Ide\Resource::get('form::Cmatrix/Web/custom/master/res/favicon.ico')->Link,
            ]
        ];
    }

    // --- --- --- --- --- --- --- ---
	protected function getMyDescriptor(){
        return $this->Config->getValue('app/code') !== 'cmatrix' ? 
            $this->Config->getValue('app/info') : 
            \Cmatrix\Kernel\Config::get(\Cmatrix\Kernel\Ide\Part::get('Cmatrix/App')->Path.CM_DS.'app.config.json')->getValue('app/info');
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