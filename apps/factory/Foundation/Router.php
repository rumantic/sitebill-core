<?php
namespace factory\Foundation;

class Router {
    function urlAnalizer() {
        $topic_id = FALSE;
        if (preg_match('/topic(\d*).html/', $_SERVER['REQUEST_URI'], $matches)) {
            $topic_id = $matches[1];
        } elseif ($x = $this->topicUrlFind($_SERVER['REQUEST_URI'])) {
            $topic_id = $x;
        } else {
            $topic_id = FALSE;
        }
        return $topic_id;
    }

    function topicUrlFind($request_uri) {

        $url_parts = parse_url(urldecode($request_uri));

        $path = $url_parts['path'];
        if (substr($path, 0, 1) === '/') {
            $path = substr($path, 1);
        }
        if (substr($path, -1, 1) === '/') {
            $path = substr($path, 0, strlen($path) - 1);
        }


        $topic_name = str_replace('/', '', $url_parts['path']);


        $topic_name = $path;


        $topic_name = \system\lib\SiteBill::getClearRequestURI();
        if ($topic_name == '') {
            return false;
        }
        $Structure = \system\lib\SiteBill::structure_instance();
        $urls = $Structure->loadCategoriesUrls();
        $Config = \system\lib\SiteBill::config_instance();

        if ($Config::getValue('apps.seo.level_enable') == 1) {

        } else {
            foreach ($urls as $k => $u) {
                $up = explode('/', $u);
                $urls[$k] = end($up);
            }
        }

        $urls_to_ids = array_flip($urls);
        if (isset($urls_to_ids[$topic_name])) {
            return $urls_to_ids[$topic_name];
        } else {
            return FALSE;
        }
        if (strlen($topic_name) > 0) {
            $DBC = DBC::getInstance();
            $query = 'SELECT id FROM ' . DB_PREFIX . '_topic WHERE url=? LIMIT 1';
            $stmt = $DBC->query($query, array($topic_name));

            if ($stmt) {
                $ar = $DBC->fetch($stmt);
                return $ar['id'];
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}
