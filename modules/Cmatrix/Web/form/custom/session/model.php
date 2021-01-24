<?php
class MyModel extends \Cmatrix\Web\Mvc\Model {
    
    // --- --- --- --- --- --- --- ---
    public function getData(){
        $Data = [
            'page' => [
                'name' => \Cmatrix\Web\Ide\Page::get('Cmatrix/Web/custom/session')->Config->getValue('page/name'),
            ],
            'web' => [
                'browser' => \Cmatrix\Web\Page::get('browser')->Path,
            ],            
            'label' => 'Код,имя',
            'code' => \Cmatrix\App\Kernel::get()->Config->getValue('app/code'),
            'name' => \Cmatrix\App\Kernel::get()->Config->getValue('app/name'),
            'session' => $this->getMySession(),
            'sysuser' => $this->getMySysuser(),
        ];
        
        //dump($Data);
        return $Data;
    }
    
    // --- --- --- --- --- --- --- ---
    public function getMySession(){
        $AviProps = ['id','hid','create_ts','touch_ts','ip4','ip4x','proxy','sysuser_id']; 
        $Session = \Cmatrix\Core\Session::get()->Values;
        $Session = array_intersect_key($Session,array_flip($AviProps));
        //dump($Session);
        
        $Props = \Cmatrix\Core\Session::get()->Dm->Props;
        $Props = array_intersect_key($Props,array_flip($AviProps));
        //dump($Props);
        
        $Now = new \DateTime('now');
        
        $_cduration = function() use($Now,$Session){
            $TS = new \DateTime($Session['create_ts']);
            $Interval = $TS->diff($Now);
            return $Interval->format('дней:%R%a, часов:%H, минут:%I');
        };
        
        $_tduration = function() use($Now){
            
        };
        
        return [
            'values' => $Session,
            'props'=> $Props,
            'duration' => [
                'create' => $_cduration(),
                'touch'  => $_tduration()
            ]
        ];
    }
    
    // --- --- --- --- --- --- --- ---
    public function getMySysuser(){
        $AviProps = ['id','hid','code','name']; 
        
        $Sysuser = \Cmatrix\Core\Sysuser::get()->Values;
        $Sysuser = array_intersect_key($Sysuser,array_flip($AviProps));
        
        $Props = \Cmatrix\Core\Sysuser::get()->Dm->Props;
        $Props = array_intersect_key($Props,array_flip($AviProps));
        
        return [
            'values' => $Sysuser,
            'props'=> $Props
        ];
    }
}
?>