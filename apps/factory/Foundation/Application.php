<?php
namespace factory\Foundation;

use factory\Foundation\AbstractKernel;

class Application {
    function __construct() {

    }

    function make () {
        $kernel = new AbstractKernel;

        return $kernel;
    }
}
