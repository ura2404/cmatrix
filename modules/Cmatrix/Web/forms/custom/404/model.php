<?php
class MyModel extends \Cmatrix\Web\Mvc\Model {
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'web' => [
                'favicon' => \Cmatrix\Web\Resource::get('Cmatrix/Web/forms/custom/404/res/favicon.ico')->Path,
            ],
            'page' => [
                'name' => '404',
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
        $Pics = \Cmatrix\Kernel\Ide\Form::get('Cmatrix/Web/forms/custom/404/res/404')->Files;
        $Key = array_rand($Pics);
        return \Cmatrix\Web\Resource::get('Cmatrix/Web/forms/custom/404/res/404/'.$Pics[$Key])->Path;
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