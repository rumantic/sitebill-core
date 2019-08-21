<?php
namespace factory\Foundation;

use factory\Foundation\AbstractKernel;
use system\lib\SiteBill;

class Application {
    /**
     * @var \system\lib\template\Template
     */
    private $template;

    function __construct() {

    }

    public function make () {
        $sitebill = new SiteBill();
        $this->template = $sitebill->get_template_instance();
    }

    public function sendResponse () {
        $this->template->display('main.tpl');

    }

}
