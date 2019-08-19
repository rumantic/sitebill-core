<?php

namespace factory\Foundation;


class Environment {
    function get_document_root () {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    function load_db_inc_file () {
        require_once ($this->get_document_root().'/inc/db.inc.php');
    }

    function load () {
        $this->load_db_inc_file();
    }

}
