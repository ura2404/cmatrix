<?php
class MyModel extends \Cmatrix\Web\Mvc\Model {
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'web' => [
                'favicon' => \Cmatrix\Web\Resource::get('Cmatrix/Web/resources/icons/404.ico')->Path,
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
        $Root = 'Cmatrix/Web/custom/404/pic';
        $Path = \Cmatrix\Kernel\Ide\Resource::get($Root)->Path;
        
        $Files = array_diff(scandir($Path),['.','..']);
        $Files = array_filter($Files,function($value) use($Path){
            $Path = $Path .'/'. $value;
            return is_dir($Path) && $value{0} !== '_' ? false : true;
        });
        
        $Key = array_rand($Files);
        
        return \Cmatrix\Kernel\Ide\Resource::get($Root.'/'.$Files[$Key])->Wpath;
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