<?php

namespace clases;
use DI2\DI2;

trait Init {
    use DI2;

    function jsonFilter(){
        return new JsonLoader('test.json');
    }

}
