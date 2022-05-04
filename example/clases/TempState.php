<?php

namespace clases;

class TempState extends State {

    function __construct(){
        $this->say = 'Hello World';
    }

    protected function get(){
        return $this->say;
    }
}