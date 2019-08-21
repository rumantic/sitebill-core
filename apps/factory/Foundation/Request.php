<?php


namespace factory\Foundation;


use DBC;
use system\lib\SiteBill;
use system\lib\admin\structure\Structure_Manager;
use factory\Foundation\ConfigAndRequest;

class Request extends ConfigAndRequest {
    function _detectUrlParams($server_request_uri) {

        $server_request_uri = urldecode($server_request_uri);
        $server_request_uri = parse_url($server_request_uri, PHP_URL_PATH);
        $topic_id = FALSE;
        $city_id = FALSE;
        $gorod_name = FALSE;

        $server_request_uri = SiteBill::getClearRequestURI();

        if (preg_match('/topic(\d*).html/', $server_request_uri, $matches) && $this->isTopicExists($matches[1])) {
            //$this->setRequestValue('topic_id', $matches[1]);
            $topic_id = (int) $matches[1];
            require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/structure/structure_manager.php');
            $Structure = new Structure_Manager();
            $urls = $Structure->loadCategoriesUrls();
            //print_r($urls);
            if (isset($urls[$topic_id]) && $urls[$topic_id] != '') {
                header('location:' . SITEBILL_MAIN_URL . '/' . $urls[$topic_id]);
                exit();
            }
        } else {
            if ($x = $this->cityTopicUrlFind($server_request_uri)) {
                $topic_id = $x[1];
                $city_id = $x[0];
                $gorod_name = $x[2];
            } elseif ($x = $this->topicUrlFind($server_request_uri)) {
                $topic_id = $x;
            } else {
                if ($this->getConfigValue('apps.seo.level_enable') == 1) {
                    $ru = $server_request_uri;
                    if (substr($ru, 0, 1) === '/') {
                        $ru = substr($ru, 1);
                    }
                    if (substr($ru, -1, 1) === '/') {
                        $ru = substr($ru, 0, strlen($ru) - 1);
                    }
                    //$ru=trim($server_request_uri,'/');
                    if (SITEBILL_MAIN_URL != '') {
                        $ru = str_replace(trim(SITEBILL_MAIN_URL, '/') . '/', '', $ru);
                    }
                    require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/structure/structure_manager.php');
                    $Structure = new Structure_Manager();
                    $urls = $Structure->loadCategoriesUrls();

                    $urls_to_ids = array_flip($urls);

                    $parts = explode('?', $ru);

                    if (strlen($parts[0]) > 0) {
                        if (isset($urls_to_ids[$parts[0]])) {

                            //$this->setRequestValue('topic_id', $urls_to_ids[$parts[0]]);
                            $topic_id = $urls_to_ids[$parts[0]];
                        }
                    }
                }
            }
        }
        return array(
            'topic_id' => $topic_id,
            'city_id' => $city_id,
            'gorod_name' => $gorod_name,
        );
    }

    function cityTopicUrlFind($request_uri) {
        $request_uri = urldecode($request_uri);

        $cid = NULL;
        $tid = NULL;
        $request_uri = trim($request_uri, '/');
        if (strpos($request_uri, '-') != false) {
            $request_uri = str_replace('.html', '', $request_uri);
            $parts = array();
            $parts = explode('-', $request_uri);
            /* print_r($parts); */
            $parts_count = count($parts);
            for ($i = 1; $i < $parts_count; $i++) {
                $cid = NULL;
                $tid = NULL;
                $city_name = '';

                $left_part = array();
                $right_part = array();
                $left_part = array_slice($parts, 0, $i);
                $right_part = array_slice($parts, $i);

                $DBC = DBC::getInstance();
                $query = 'SELECT city_id, name FROM ' . DB_PREFIX . '_city WHERE translit_name=? LIMIT 1';

                $stmt = $DBC->query($query, array(implode('-', $left_part)));


                if ($stmt) {
                    $ar = $DBC->fetch($stmt);
                    $cid = $ar['city_id'];
                    $city_name = $ar['name'];
                }

                $query = 'SELECT id FROM ' . DB_PREFIX . '_topic WHERE translit_name=?';
                $stmt = $DBC->query($query, array(implode('-', $right_part)));
                if ($stmt) {
                    $ar = $DBC->fetch($stmt);
                    $tid = $ar['id'];
                }

                if ($cid !== NULL && $tid != NULL) {
                    return array($cid, $tid, $city_name);
                }
            }
            return FALSE;
        }
        return FALSE;
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


        $topic_name = SiteBill::getClearRequestURI();
        if ($topic_name == '') {
            return false;
        }
        require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/structure/structure_manager.php');
        $Structure = new Structure_Manager();
        $urls = $Structure->loadCategoriesUrls();

        if ($this->getConfigValue('apps.seo.level_enable') == 1) {

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
