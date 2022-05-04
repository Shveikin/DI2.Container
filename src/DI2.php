<?php

namespace DI2;

trait DI2 {
    public function __get($alias) {
        if (property_exists($this, $alias)) {
            return $this->$alias;
        } else {
            if (method_exists($this, $alias)){
                return MP::DI2($this, $alias);
            }
        }
    }

    static function __init__($from, $alias){
        return $from->{$alias}();
    }
}