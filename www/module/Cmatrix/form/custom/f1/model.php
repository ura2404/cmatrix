<?php

class MyModel {
    // --- --- --- --- --- --- --- ---
    public function getData(){
        return [
            'f1' => 'form1',
            'f' => 'f1',
            'q' => [
                'f' => 'q-f1',
                'q' => [
                    'f' => 'q-q-f1'
                ]
            ]
        ];
    }
}
?>