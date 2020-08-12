<?php

class MyModel extends \Cmatrix\Mvc\Model {
    protected $Config;
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        header('HTTP/1.1 404 Page not found.',true,404);
        
        return [
            'page' => [
                'name' => '404',
                'favicon' => \Cmatrix\Kernel\Ide\Resource::get('Cmatrix/icons/404.ico')->Wpath,
                'refer' => \Cmatrix\Kernel\Kernel::$PAGE
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
            "Страницу, которую ты ищешь\nНайти невозможно, но\nВедь не счесть других…"
        ];
        
        $Key = array_rand($Arr);
        
        return explode("\n",$Arr[$Key]);
    }
    
}
?>