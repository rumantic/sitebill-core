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
    public function gatherRequestParams() {
        $REQUESTURIPATH = SiteBill::getClearRequestURI();
        $params = array();

        /* if(NULL!==$this->getRequestValue('places')){
          $params['places'] = $this->getRequestValue('places');
          } */

        if (NULL !== $this->getRequestValue('id')) {
            if (is_array($this->getRequestValue('id'))) {
                $params['id'] = $this->getRequestValue('id');
            } else {
                $params['id'] = (int) $this->getRequestValue('id');
            }
        }

        if (NULL !== $this->getRequestValue('wlocation')) {
            $params['wlocation'] = $this->safeRequestParams($this->getRequestValue('wlocation'));
        }
        if (NULL !== $this->getRequestValue('loc')) {
            $params['loc'] = $this->safeRequestParams($this->getRequestValue('loc'));
        }
        if (NULL !== $this->getRequestValue('topic_id')) {
            $params['topic_id'] = $this->safeRequestParams($this->getRequestValue('topic_id'));
        }
        if (NULL !== $this->getRequestValue('order')) {
            $params['order'] = $this->getRequestValue('order');
        }
        if (NULL !== $this->getRequestValue('region_id')) {
            $params['region_id'] = $this->safeRequestParams($this->getRequestValue('region_id'));
        }
        if (NULL !== $this->getRequestValue('city_id')) {
            $params['city_id'] = $this->safeRequestParams($this->getRequestValue('city_id'));
        }
        if (NULL !== $this->getRequestValue('district_id')) {
            $params['district_id'] = $this->safeRequestParams($this->getRequestValue('district_id'));
        }
        if (NULL !== $this->getRequestValue('country_id')) {
            $params['country_id'] = $this->safeRequestParams($this->getRequestValue('country_id'));
        }
        if (NULL !== $this->getRequestValue('metro_id')) {
            $params['metro_id'] = $this->safeRequestParams($this->getRequestValue('metro_id'));
        }

        if (NULL !== $this->getRequestValue('street_id')) {
            $params['street_id'] = $this->safeRequestParams($this->getRequestValue('street_id'));
        }


        if ($this->getConfigValue('apps.complex.enable') && NULL !== $this->getRequestValue('complex_id')) {
            $params['complex_id'] = $this->safeRequestParams($this->getRequestValue('complex_id'));
        }
        if (NULL !== $this->getRequestValue('page')) {
            $params['page'] = (int) $this->getRequestValue('page');
        }
        if (NULL !== $this->getRequestValue('spec')) {
            $params['spec'] = $this->getRequestValue('spec');
        }
        if (NULL !== $this->getRequestValue('owner')) {
            $params['owner'] = (int) $this->getRequestValue('owner');
        }
        if (NULL !== $this->getRequestValue('asc')) {
            $params['asc'] = $this->getRequestValue('asc');
        }


        if (NULL !== $this->getRequestValue('user_id')) {
            $params['user_id'] = $this->getRequestValue('user_id');
        }

        if (NULL !== $this->getRequestValue('currency_id')) {
            $params['currency_id'] = (int) $this->getRequestValue('currency_id');
        }
        if (NULL !== $this->getRequestValue('price')) {
            $params['price'] = (int) str_replace(' ', '', $this->getRequestValue('price'));
            $this->template->assign('price', $params['price']);
        }

        if (NULL !== $this->getRequestValue('price_min')) {
            $params['price_min'] = (int) str_replace(' ', '', $this->getRequestValue('price_min'));
            $this->template->assign('price_min', $params['price_min']);
        }

        if (NULL !== $this->getRequestValue('price_pm')) {
            $params['price_pm'] = (int) str_replace(' ', '', $this->getRequestValue('price_pm'));
            $this->template->assign('price_pm', $params['price_pm']);
        }

        if (NULL !== $this->getRequestValue('price_pm_min')) {
            $params['price_pm_min'] = (int) str_replace(' ', '', $this->getRequestValue('price_pm_min'));
            $this->template->assign('price_pm_min', $params['price_pm_min']);
        }

        if (NULL !== $this->getRequestValue('house_number')) {
            $params['house_number'] = $this->getRequestValue('house_number');
            $this->template->assign('house_number', $params['house_number']);
        }

        if (NULL !== $this->getRequestValue('onlyspecial')) {
            $params['onlyspecial'] = $this->getRequestValue('onlyspecial');
            $this->template->assign('onlyspecial', $params['onlyspecial']);
        }

        if (NULL !== $this->getRequestValue('floor')) {
            $params['floor'] = (int) $this->getRequestValue('floor');
        }

        if (NULL !== $this->getRequestValue('floor_count')) {
            $params['floor_count'] = (int) $this->getRequestValue('floor_count');
        }

        if (NULL !== $this->getRequestValue('floor_min')) {
            $params['floor_min'] = (int) $this->getRequestValue('floor_min');
        }

        if (NULL !== $this->getRequestValue('floor_max')) {
            $params['floor_max'] = (int) $this->getRequestValue('floor_max');
        }

        if (NULL !== $this->getRequestValue('floor_count_min')) {
            $params['floor_count_min'] = (int) $this->getRequestValue('floor_count_min');
        }

        if (NULL !== $this->getRequestValue('floor_count_max')) {
            $params['floor_count_max'] = (int) $this->getRequestValue('floor_count_max');
        }

        if (NULL !== $this->getRequestValue('not_first_floor')) {
            $params['not_first_floor'] = (int) $this->getRequestValue('not_first_floor');
        }

        if (NULL !== $this->getRequestValue('not_last_floor')) {
            $params['not_last_floor'] = (int) $this->getRequestValue('not_last_floor');
        }


        if (NULL !== $this->getRequestValue('square_min')) {
            $params['square_min'] = (int) $this->getRequestValue('square_min');
        }

        if (NULL !== $this->getRequestValue('square_max')) {
            $params['square_max'] = (int) $this->getRequestValue('square_max');
        }

        if (NULL !== $this->getRequestValue('live_square_min')) {
            $params['live_square_min'] = (int) $this->getRequestValue('live_square_min');
        }

        if (NULL !== $this->getRequestValue('kitchen_square_min')) {
            $params['kitchen_square_min'] = (int) $this->getRequestValue('kitchen_square_min');
        }

        if (NULL !== $this->getRequestValue('kitchen_square_max')) {
            $params['kitchen_square_max'] = (int) $this->getRequestValue('kitchen_square_max');
        }

        if (NULL !== $this->getRequestValue('live_square_max')) {
            $params['live_square_max'] = (int) $this->getRequestValue('live_square_max');
        }

        if (NULL !== $this->getRequestValue('is_phone')) {
            $params['is_phone'] = (int) $this->getRequestValue('is_phone');
        }

        if (NULL !== $this->getRequestValue('is_balkony')) {
            $params['is_balkony'] = (int) $this->getRequestValue('is_balkony');
        }

        if (NULL !== $this->getRequestValue('is_sanitary')) {
            $params['is_sanitary'] = (int) $this->getRequestValue('is_sanitary');
        }


        if (NULL !== $this->getRequestValue('status')) {
            $params['status'] = (int) $this->getRequestValue('status');
        }


        if (NULL !== $this->getRequestValue('nout_from_sale')) {
            $params['nout_from_sale'] = (int) $this->getRequestValue('nout_from_sale');
        }

        if (NULL !== $this->getRequestValue('nwith_null_params')) {
            $params['nwith_null_params'] = (int) $this->getRequestValue('nwith_null_params');
        }

        if (NULL !== $this->getRequestValue('by_ipoteka')) {
            $params['by_ipoteka'] = (int) $this->getRequestValue('by_ipoteka');
        }

        if (NULL !== $this->getRequestValue('new_only')) {
            $params['new_only'] = (int) $this->getRequestValue('new_only');
        }

        if (NULL !== $this->getRequestValue('is_furniture')) {
            $params['is_furniture'] = (int) $this->getRequestValue('is_furniture');
        }

        if (NULL !== $this->getRequestValue('has_photo')) {
            $params['has_photo'] = (int) $this->getRequestValue('has_photo');
        }

        if (NULL !== $this->getRequestValue('is_internet')) {
            $params['is_internet'] = (int) $this->getRequestValue('is_internet');
        }

        if (NULL !== $this->getRequestValue('room_count')) {
            $params['room_count'] = $this->getRequestValue('room_count');
        }

        if (NULL !== $this->getRequestValue('optype') && null !== $this->getRequestValue('optype')) {
            $params['optype'] = $this->safeRequestParams($this->getRequestValue('optype'));
        }

        if (NULL !== $this->getRequestValue('minbeds')) {
            $params['minbeds'] = (int) $this->getRequestValue('minbeds');
        }

        if (NULL !== $this->getRequestValue('minbaths')) {
            $params['minbaths'] = (int) $this->getRequestValue('minbaths');
        }

        if (NULL !== $this->getRequestValue('uniq_id')) {
            $params['uniq_id'] = (int) $this->getRequestValue('uniq_id');
        }



        if (1 == (int) $this->getRequestValue('export_afy')) {
            $params['export_afy'] = 1;
        }
        if (1 == (int) $this->getRequestValue('export_cian')) {
            $params['export_cian'] = 1;
        }

        if (NULL !== $this->getRequestValue('extended_search')) {
            $params['extended_search'] = $this->getRequestValue('extended_search');
        }
        if (NULL !== $this->getRequestValue('search')) {
            $params['search'] = $this->getRequestValue('search');
        }
        if (NULL !== $this->getRequestValue('srch_word')) {
            $params['srch_word'] = $this->getRequestValue('srch_word');
        }



        if (0 != (int) $this->getRequestValue('page_limit')) {
            $params['page_limit'] = (int) $this->getRequestValue('page_limit');
        }

        if (NULL !== $this->getRequestValue('geocoords')) {
            $params['geocoords'] = preg_replace('/[^0-9.+-:]/', '', $this->getRequestValue('geocoords'));
            if ($params['geocoords'] == '') {
                unset($params['geocoords']);
            }
        }


        if (file_exists(SITEBILL_DOCUMENT_ROOT . '/apps/billing/lib/billing.php') && $this->getConfigValue('apps.billing.enable') == 1) {
            if (NULL !== $this->getRequestValue('vip_status')) {
                $params['vip_status'] = (int) $this->getRequestValue('vip_status');
            }
            if (NULL !== $this->getRequestValue('premium_status')) {
                $params['premium_status'] = (int) $this->getRequestValue('premium_status');
            }
            if (NULL !== $this->getRequestValue('bold_status')) {
                $params['bold_status'] = (int) $this->getRequestValue('bold_status');
            }
        }

        /* if($REQUESTURIPATH=='find'){
          $params['pager_url']=$REQUESTURIPATH;
          } */

        return $params;
    }

}
