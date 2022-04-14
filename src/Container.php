<?php

namespace DI2;



class MP {
    static $container = false;
    private $containers = [];
    private $reg = [];

    static function GET(...$props){
        if (self::$container==false)
            self::$container = new self();

        $class = isset($props['class'])?$props['class']:(isset($props[0])?$props[0]:false);
        $alias = isset($props['alias'])?$props['alias']:(isset($props[1])?$props[1]:false);
        $constructor = isset($props['constructor'])?$props['constructor']:(isset($props[2])?$props[2]:false);

        if ($class!=false){
            return self::$container->class($class, $alias, (array)$constructor);
        }

        return self::$container;
    }

    function autoprops($class, $alias, &$constructor):array {
        $parameters = (new \ReflectionClass($class))->getConstructor()->getParameters();
                
        $result = [];
        $propsCounter = 0;
        foreach ($parameters as $p){
            if ($p->name=='super'){
                $result[] = function($el) use ($alias){
                    $this->containers[$alias] = $el;
                };
            } else {
                if (isset($constructor[$p->name])){
                    $result[] = $constructor[$p->name];
                } else 
                if (isset($constructor[$propsCounter])){
                    $result[] = $constructor[$propsCounter];
                } else {
                    $result[] = false;
                }

                $propsCounter++;
            }
        }

        return $result;
    }

    function class(string $class, string|bool $alias = false, array $constructor = []){
        if ($alias==false) $alias = $class;

        if (!isset($this->reg[$alias])){
            if (!isset($this->containers[$alias])){
                $this->reg[$alias] = true;
                
                $tempElement = new $class(...$this->autoprops($class, $alias, $constructor));

/* 
                if (method_exists($tempElement, 'construct')){
                    $tempElement->construct(...(array)$construct);
                }
*/

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