<?php

namespace DI2;

trait DI2 {
    public function __get($alias) {
        if (!property_exists($this, $alias)) {
            $uses = false;
            foreach (class_uses($this) as $use) {
                if (method_exists($use, $alias)){
                    $this->$alias = MP::DI2($this, $alias);
                    $uses = true;
                    break;
                }
            }

            if (!$uses){
                if (method_exists($this, '__anyKey')){
                    return $this->__anyKey($alias);
                }
            }
            /* 
            if (method_exists($this, $alias)){
                $this->$alias = MP::DI2($this, $alias);
            } else {
                throw new \Exception(static::class . "->$alias - not exists ");
            } 
            */
        }
        
        return $this->$alias;
    }

    static function __init__($from, $alias){
        return $from->{$alias}();
    }
}