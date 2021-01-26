<?php
class MyModel extends \Cmatrix\Web\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $this->Config = \Cmatrix\App\Kernel::get()->Config;
        
        return [
            'app' => [
                'name'    => $this->Config->getValue('app/name'),
                'title'   => 'Master',
                'author'  => $this->Config->getValue('app/author'),
                'version' => $this->Config->getValue('app/version'),
                'period'  => $this->getMyPreiod(),
            ],
            'web' => [
                'home' => [
                    'addr'  => \Cmatrix\Web\Page::get()->Path,
                    'label' => 'Главная страница'
                ],
                'page' => [
                    'addr'  => \Cmatrix\Web\Page::get()->Path,
                    'label' => 'Оновить текущую страницу',
                ],
                'user' => [
                    'addr'  => \Cmatrix\Web\Page::get('lk')->Path,
                    'label' => \Cmatrix\Core\Sysuser::get()->name,
                    //'label' => 'Подковалихин Ю.А.',
                ],
                'favicon' => \Cmatrix\Web\Ide\Resource::get('form::Cmatrix/Web/custom/master/res/favicon.ico')->Link,
            ],
            'page' => [
                'name' => 'Master',
            ]
        ];
    }
    
    // --- --- --- --- --- --- --- ---
	protected function getMyPreiod(){
        $now = date('Y');
        $since = $this->Config->getValue('app/since',$now);
        return $now == $since ? $now : $since .' - '. $now;
	}
}
?>