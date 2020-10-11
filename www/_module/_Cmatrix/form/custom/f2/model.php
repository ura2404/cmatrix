<?php

class MyModel {
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'f2' => 'form2',
            'f' => 'f2',
            'q' => [
                'f' => 'q-f2',
                'q' => [
                    'f' => 'q-q-f2'
                ]
            ]
        ];
    }
}
?>