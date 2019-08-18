<?php
namespace factory\Foundation;

use factory\Foundation\AbstractResponse;

class AbstractKernel {
    function handle ($request) {
        $response = new AbstractResponse();

        echo 'handle<br>';
        return $response;
    }
}
