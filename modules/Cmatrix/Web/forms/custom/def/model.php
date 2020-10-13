<?php
class MyModel extends \Cmatrix\Web\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $this->Config = \Cmatrix\Kernel\Config::get('/www/config.json');
        
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
                'session' => \Cmatrix\Web\Page::get('session')->Path,
                'browser' => \Cmatrix\Web\Page::get('browser')->Path,
                'admin'   => \Cmatrix\Web\Page::get('admin')->Path,
                'check'   => \Cmatrix\Web\Page::get('check')->Path,
                //'favicon' => \Cmatrix\Kernel\Ide\Resource::get('Cmatrix/icons/def.ico')->Wpath,
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


class _MyModel extends \Cmatrix\Web\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $this->Config = \Cmatrix\Kernel\Config::get();
        
        dump(\Cmatrix\Web\Page::get('check2')->Wpath);
        
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
                'session' => \Cmatrix\Web\Page::get('session')->Wpath,
                'browser' => \Cmatrix\Web\Page::get('browser')->Wpath,
                'admin'   => \Cmatrix\Web\Page::get('admin')->Wpath,
                'check'   => \Cmatrix\Web\Page::get('check')->Wpath,
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