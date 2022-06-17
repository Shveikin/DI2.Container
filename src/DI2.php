<?php

namespace DI2;

/**
 * создайте trait для автоматической подгрузки свойств
 * В trait укажите функцию с именем свойства
 *  свойство создастся один раз для всех класов которые исользуют trait
 *  если вы хотите чтобы для каждого класса свойство было уникальным
 *  поставьте в начале функции знак "_"
 */

trait DI2
{
    public function __get($alias)
    {
        if (!property_exists($this, $alias)) {

            foreach ($this->xmergeusemethods() as $use) {
                if (method_exists($use, $alias)) {
                    $this->$alias = MP::DI2($this, $alias);
                    return $this->$alias;
                }

                if (method_exists($use, "_$alias")) {
                    $this->$alias = $this->{"_$alias"}();
                    return $this->$alias;
                }
            }

            if (method_exists($this, '__anyKey')) {
                $this->$alias = $this->__anyKey($alias);
                return $this->$alias;
            }
        }

        return $this->$alias;
    }

    private function xmergeusemethods()
    {
        foreach (class_uses($this) as $use) {
            yield $use;
        }

        foreach (class_parents($this) as $parent) {
            foreach (class_uses($parent) as $use) {
                yield $use;
            }
        }

        return false;
    }

    public static function __init__($from, $alias)
    {
        return $from->{$alias}();
    }
}
