<?php

namespace DI2;



class MP {
    static $container = false;
    private $containers = [];
    private $reg = [];

    static function GET($class = false, $alias = false){
        if (self::$container==false)
            self::$container = new self();

        if ($class!=false){
            return self::$container->class($class, $alias);
        }

        return self::$container;
    }

    function class($class, $alias = false){
        if ($alias==false) $alias = $class;

        if (!isset($this->reg[$alias])){
            if (!isset($this->containers[$alias])){
                $this->reg[$alias] = true;

                $tempElement = new $class(function($el) use ($alias){
                    $this->containers[$alias] = $el;
                });

                if (method_exists($tempElement, '__parent')){
                    $tempElement->__parent();
                }

                if (!isset($this->containers[$alias])){
                    $this->containers[$alias] = $tempElement;
                }
            }
        } else {
            if (!isset($this->containers[$alias]))
                throw new \Exception(" $class не реальзует функцию \$super(\$this) в __construct ", 1);
        }

        return $this->containers[$alias];
    }
}

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
            // $parameters = $method->getParameters();

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