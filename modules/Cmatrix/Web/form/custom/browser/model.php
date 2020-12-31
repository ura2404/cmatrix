<?php
class MyModel extends \Cmatrix\Web\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'page' => [
                'name' => \Cmatrix\Web\Ide\Page::get('Cmatrix/Web/custom/browser')->Config->getValue('page/name'),
            ],
            'browsers' => $this->getMyBrowsers(),
        ];
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyBrowsers(){
        return [
            'Firefox' => [
                'icon' => 'fab fa-fw fa-firefox',
                'url' =>'http://www.mozilla.org',
            ],
            'Chrome' => [
                'icon' => 'fab fa-fw fa-chrome',
                'url' => 'http://www.google.com/chrome/',
            ],
            'Opera' => [
                'icon' => 'fab fa-fw fa-opera',
                'url' => 'http://www.opera.com',
            ]
        ];
    }
}
?>