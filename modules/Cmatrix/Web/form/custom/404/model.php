<?php
class MyModel extends \Cmatrix\Web\Mvc\Model {
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'web' => [
                'favicon' => \Cmatrix\Web\Ide\Resource::get('form::Cmatrix/Web/custom/404/res/favicon.ico')->Link,
            ],
            'page' => [
                'name' => '404',
                'refer' => strpos(\Cmatrix\Web\Page::$PAGE,'/') === false ? \Cmatrix\Web\Page::$PAGE : strRafter(\Cmatrix\Web\Page::$PAGE,'/'),
                'exception' => CM_MODE === 'development' ? \Cmatrix\Web\Page::$EXCEPTION : null
            ],
            'pic' => [
                'random' => $this->getMyPic(),
            ],
            'text' => [
                'random' => $this->getMyText()
            ]
        ];
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPic(){
        $Arr = [
            '404.2.png',
            '404.3.png',
            '404.4.png',
            '404.5.png',
            '404.6.png',
        ];
        
        $Key = array_rand($Arr);
        return \Cmatrix\Web\Ide\Resource::get('form::Cmatrix/Web/custom/404/res/404/'.$Arr[$Key])->Link;
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyText(){
        $Arr = [
            "Ты вступаешь в реку,\nНо река не остаётся прежней...\nЭтой web-страницы здесь уже нет.",
            "Страницу, которую ты ищешь\nНайти невозможно, но\nВедь не счесть других…"
        ];
        
        $Key = array_rand($Arr);
        
        return explode("\n",$Arr[$Key]);
    }
}
?>