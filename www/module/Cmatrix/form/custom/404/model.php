<?php

class MyModel extends \Cmatrix\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'page' => [
                'name' => '404'
            ],
            'web' => [
                'favicon' => \Cmatrix\Kernel\Ide\Resource::get('Cmatrix/icons/404.ico')->Wpath,
                'page' => \Cmatrix\Kernel\Kernel::$PAGE
            ],
            'pic' => [
                'random' => $this->getMyPic(),
                //'404' => \Cmatrix\Kernel\Ide\Resource::get('Cmatrix/custom/404/404-1.jpg')->Wpath
            ],
            'text' => $this->getMyText()
        ];
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPic(){
        $Root = 'Cmatrix/custom/404/pic';
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
            "Страница, которую ты ищешь\nНайти невозможно, но\nВедь не счесть других…"
        ];
        
        $Key = array_rand($Arr);
        
        return explode("\n",$Arr[$Key]);
    }
    
}
?>