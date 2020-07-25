<?php

class MyModel {
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'f1' => 'form2',
            'f' => 'f2',
            'q' => [
                'f' => 'q-f2',
                'qq' => [
                    'f' => 'q-qq-f2'
                ]
            ]
        ];
    }
}
?>