<?php

namespace clases;

class JsonLoader {

    private $data = [];
    private $isExists = true;
    private $prefix = '>> ';

    function __construct($file){
        if (file_exists($file))
            $this->data = json_decode(file_get_contents($file), true);
        else 
            $this->isExists = false;    
    }

    function isExists(): bool{
        return $this->isExists;
    }

    function print($text){
        return $this->prefix . $text . "\n";
    }

}