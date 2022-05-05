<?php

namespace DI2;


trait Container {
    static function main(){
        return MP::GET(static::class);
    }

    static function __callStatic($func, $arguments){
        $class = MP::GET(static::class);
        return $class->__apply($func, $arguments);
    }

    function __apply($func, $arguments){
        if (method_exists($this, $func)){
            $method = new \ReflectionMethod($this, $func);
            $method->setAccessible(true);
            return $method->invoke($this, ...$arguments);
        } else {
            return $this->__any($func, $arguments);
        }
    }

    function __call($func, $arguments){
        return $this->__apply($func, $arguments);
    }

    function __any($func, $arguments){
        throw new \Exception(get_class($this) . " method $func - отсутствует ");
    }

}