<?php
namespace factory\Foundation;

use factory\Foundation\AbstractKernel;

class Application {
    function __construct() {

    }

    public function make () {
        $kernel = new AbstractKernel;

        return $kernel;
    }

    public function sendResponse () {

    }
}
