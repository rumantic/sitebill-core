<?php
namespace factory\Foundation;
use system\lib\system\DBC;
use system\lib\system\multilanguage\Multilanguage;

class MetaTags extends ConfigAndRequest {
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Template
     */
    private $template;

    function __construct(Request $request, \system\lib\template\Template $template) {
        parent::__construct();
        $this->request = $request;
        $this->template = $template;
    }

    function compile ($REQUESTURIPATH, $any_url_catched) {
        if ($REQUESTURIPATH == 'find') {
            //$grid_constructor->setCatchedRoute('system:find');
            //$SF->addFeedback('catched_route', 'system:find');
            $find_url_catched = true;
            $any_url_catched = true;
            //$params['pager_url']='find';
        } elseif ($REQUESTURIPATH != '') {
            $DBC = DBC::getInstance();
            if (!$any_url_catched) {
                if (file_exists(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . '/main/router/router.php')) {
                    require_once SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . '/main/router/router.php';
                    $Router = new Router();
                    if ($Router->checkUrl($REQUESTURIPATH)) {
                        $route_catched = true;
                        $any_url_catched = true;
                        $work_params = $Router->getWorkParams();
                        foreach ($work_params['params'] as $k => $v) {
                            $this->setRequestValue($k, $v);
                        }
                    }
                }
            }


            if (!$any_url_catched) {
                if (file_exists(SITEBILL_DOCUMENT_ROOT . '/apps/predefinedlinks/admin/admin.php')) {
                    require_once SITEBILL_DOCUMENT_ROOT . '/apps/predefinedlinks/admin/admin.php';
                    $PDLA = new predefinedlinks_admin();
                    if ($predefined_info = $PDLA->checkAlias($REQUESTURIPATH)) {
                        $predefined_url_catched = true;
                        $any_url_catched = true;
                        //$grid_constructor->setCatchedRoute('system:predefinedlinks');
                        //$grid_constructor->setCatchedRouteParams($predefined_info);
                    }
                }
            }

            if (!$any_url_catched) {
                if (intval($this->getConfigValue('apps.seo.no_country_url')) === 0) {
                    $query = 'SELECT * FROM ' . DB_PREFIX . '_country WHERE url=? LIMIT 1';
                    $stmt = $DBC->query($query, array($REQUESTURIPATH));
                    if ($stmt) {
                        $ar = $DBC->fetch($stmt);

                        if ((int) $ar['country_id'] != 0) {
                            $country_url_catched = true;
                            $country_info = $ar;
                            $any_url_catched = true;
                        }
                    }
                }
            }

            if (!$any_url_catched) {
                if (intval($this->getConfigValue('apps.seo.no_region_url')) === 0) {
                    $query = 'SELECT * FROM ' . DB_PREFIX . '_region WHERE alias=? LIMIT 1';
                    $stmt = $DBC->query($query, array($REQUESTURIPATH));
                    if ($stmt) {
                        $ar = $DBC->fetch($stmt);
                        if ((int) $ar['region_id'] != 0) {
                            $region_url_catched = true;
                            $region_info = $ar;
                            $any_url_catched = true;
                        }
                    }
                }
            }

            if (!$any_url_catched) {
                if (intval($this->getConfigValue('apps.seo.no_city_url')) === 0) {
                    $query = 'SELECT * FROM ' . DB_PREFIX . '_city WHERE url=? LIMIT 1';
                    $stmt = $DBC->query($query, array($REQUESTURIPATH));
                    if ($stmt) {
                        $ar = $DBC->fetch($stmt);

                        if ((int) $ar['city_id'] != 0) {
                            $city_url_catched = true;
                            $city_info = $ar;
                            $any_url_catched = true;
                        }
                    }
                }
            }

            if (!$any_url_catched) {
                if (intval($this->getConfigValue('apps.seo.no_metro_url')) === 0) {
                    $query = 'SELECT * FROM ' . DB_PREFIX . '_metro WHERE `alias`=? LIMIT 1';
                    $stmt = $DBC->query($query, array($REQUESTURIPATH));
                    if ($stmt) {
                        $ar = $DBC->fetch($stmt);

                        if ((int) $ar['metro_id'] != 0) {
                            $metro_url_catched = true;
                            $metro_info = $ar;
                            $any_url_catched = true;
                        }
                    }
                }
            }

            if (!$any_url_catched) {
                if (intval($this->getConfigValue('apps.seo.no_district_url')) === 0) {
                    $query = 'SELECT * FROM ' . DB_PREFIX . '_district WHERE `url`=? LIMIT 1';
                    $stmt = $DBC->query($query, array($REQUESTURIPATH));
                    if ($stmt) {
                        $ar = $DBC->fetch($stmt);

                        if ((int) $ar['id'] != 0) {
                            $district_url_catched = true;
                            $district_info = $ar;
                            $any_url_catched = true;
                        }
                    }
                }
            }

            if (!$any_url_catched) {
                if ($this->getConfigValue('apps.complex.enable')) {
                    $DBC = DBC::getInstance();
                    $query = 'SELECT * FROM ' . DB_PREFIX . '_complex WHERE url=? LIMIT 1';
                    $stmt = $DBC->query($query, array($REQUESTURIPATH));
                    if ($stmt) {
                        $ar = $DBC->fetch($stmt);
                        if (intval($ar['complex_id']) !== 0) {
                            $complex_url_catched = true;
                            $complex_info = $ar;
                            $any_url_catched = true;
                        }
                    }
                }
            }
        }

        $gorod_name = false;
        //$grid_constructor = $this->_grid_constructor;

        if ($find_url_catched) {
            if (Multilanguage::is_set('LT_FIND_URL_TITLE', '_template')) {
                $title = Multilanguage::_('LT_FIND_URL_TITLE', '_template');
            } else {
                $title = Multilanguage::_('FIND_URL_TITLE', 'system');
            }

            $this->template->assign('title', $title);
            $this->template->assign('meta_title', $title);
            $this->setRequestValue('find_url_catched', 1);
        } elseif ($route_catched) {
            //$work_params=$Router->getWorkParams();
            //$this->setRequestValue('router_info', $work_params);
        } elseif ($predefined_url_catched) {
            if (1 === intval($this->getConfigValue('apps.language.use_langs'))) {
                $curlang = $this->getCurrentLang();
                $lang_postfix = '_' . $curlang;
                if (1 === intval($this->getConfigValue('apps.language.use_default_as_ru')) && $curlang == 'ru') {
                    $lang_postfix = '';
                }
            } else {
                $lang_postfix = '';
            }

            if (isset($predefined_info['meta_title' . $lang_postfix]) && $predefined_info['meta_title' . $lang_postfix] != '') {
                $meta_title = $predefined_info['meta_title' . $lang_postfix];
            } else {
                $meta_title = $predefined_info['meta_title'];
            }

            if (isset($predefined_info['title' . $lang_postfix]) && $predefined_info['title' . $lang_postfix] != '') {
                $title = $predefined_info['title' . $lang_postfix];
            } else {
                $title = $predefined_info['title'];
            }

            if ($meta_title == '') {
                $meta_title = $title;
            }



            if ((int) $this->getRequestValue('page') > 0 && (int) $this->getRequestValue('page') != 1 && 1 == $this->getConfigValue('add_pagenumber_title')) {
                if (0 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (1 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (2 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                }
            }

            $this->template->assign('title', $title);
            $this->template->assign('meta_title', $meta_title);

            if (isset($predefined_info['description' . $lang_postfix]) && $predefined_info['description' . $lang_postfix] != '') {
                $this->template->assign('description', $predefined_info['description' . $lang_postfix]);
            } elseif ($predefined_info['description'] != '') {
                $this->template->assign('description', $predefined_info['description']);
            }
            if (isset($predefined_info['meta_description' . $lang_postfix]) && $predefined_info['meta_description' . $lang_postfix] != '') {
                $this->template->assign('meta_description', $predefined_info['meta_description' . $lang_postfix]);
            } elseif ($predefined_info['meta_description'] != '') {
                $this->template->assign('meta_description', $predefined_info['meta_description']);
            } else {
                //$this->template->assign('meta_description', $this->getConfigValue('meta_description_main'));
            }
            if (isset($predefined_info['meta_keywords' . $lang_postfix]) && $predefined_info['meta_keywords' . $lang_postfix] != '') {
                $this->template->assign('meta_keywords', $predefined_info['meta_keywords' . $lang_postfix]);
            } elseif ($predefined_info['meta_keywords'] != '') {
                $this->template->assign('meta_keywords', $predefined_info['meta_keywords']);
            } else {
                //$this->template->assign('meta_keywords', $this->getConfigValue('meta_keywords_main'));
            }
            if (count($predefined_info['params']) > 0) {
                foreach ($predefined_info['params'] as $k => $v) {
                    $this->setRequestValue($k, $v);
                }
            }

            $this->setRequestValue('predefined_info', $predefined_info);
        } elseif ($country_url_catched) {

            if (1 === intval($this->getConfigValue('apps.language.use_langs'))) {
                $curlang = $this->getCurrentLang();
                $lang_postfix = '_' . $curlang;
                if (1 === intval($this->getConfigValue('apps.language.use_default_as_ru')) && $curlang == 'ru') {
                    $lang_postfix = '';
                }
            } else {
                $lang_postfix = '';
            }
            $meta_title = '';
            if (isset($country_info['meta_title' . $lang_postfix]) && $country_info['meta_title' . $lang_postfix] != '') {
                $meta_title = $country_info['meta_title' . $lang_postfix];
            } elseif ($country_info['meta_title'] != '') {
                $meta_title = $country_info['meta_title'];
            }


            if (isset($country_info['name' . $lang_postfix]) && $country_info['name' . $lang_postfix] != '') {
                $title = $country_info['name' . $lang_postfix];
            } else {
                $title = $country_info['name'];
            }

            if ($meta_title == '') {
                $meta_title = $title;
            }

            if ((int) $this->getRequestValue('page') > 0 && (int) $this->getRequestValue('page') != 1 && 1 == $this->getConfigValue('add_pagenumber_title')) {
                if (0 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (1 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (2 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                }
            }

            $this->template->assign('title', $title);
            $this->template->assign('meta_title', $meta_title);

            if (isset($country_info['description' . $lang_postfix]) && $country_info['description' . $lang_postfix] != '') {
                $this->template->assign('description', $country_info['description' . $lang_postfix]);
            } elseif ($country_info['description'] != '') {
                $this->template->assign('description', $country_info['description']);
            }
            if (isset($country_info['meta_description' . $lang_postfix]) && $country_info['meta_description' . $lang_postfix] != '') {
                $this->template->assign('meta_description', $country_info['meta_description' . $lang_postfix]);
            } elseif ($country_info['meta_description'] != '') {
                $this->template->assign('meta_description', $country_info['meta_description']);
            } else {
                //$this->template->assign('meta_description', $this->getConfigValue('meta_description_main'));
            }
            if (isset($country_info['meta_keywords' . $lang_postfix]) && $country_info['meta_keywords' . $lang_postfix] != '') {
                $this->template->assign('meta_keywords', $country_info['meta_keywords' . $lang_postfix]);
            } elseif ($country_info['meta_keywords'] != '') {
                $this->template->assign('meta_keywords', $country_info['meta_keywords']);
            } else {
                //$this->template->assign('meta_keywords', $this->getConfigValue('meta_keywords_main'));
            }


            $this->setRequestValue('country_id', (int) $country_info['country_id']);
            $this->setRequestValue('country_view', $REQUESTURIPATH);
        } elseif ($district_url_catched) {

            if (1 === intval($this->getConfigValue('apps.language.use_langs'))) {
                $curlang = $this->getCurrentLang();
                $lang_postfix = '_' . $curlang;
                if (1 === intval($this->getConfigValue('apps.language.use_default_as_ru')) && $curlang == 'ru') {
                    $lang_postfix = '';
                }
            } else {
                $lang_postfix = '';
            }
            $meta_title = '';
            if (isset($district_info['meta_title' . $lang_postfix]) && $district_info['meta_title' . $lang_postfix] != '') {
                $meta_title = $district_info['meta_title' . $lang_postfix];
            } elseif ($country_info['meta_title'] != '') {
                $meta_title = $district_info['meta_title'];
            }


            if (isset($district_info['name' . $lang_postfix]) && $district_info['name' . $lang_postfix] != '') {
                $title = $district_info['name' . $lang_postfix];
            } else {
                $title = $district_info['name'];
            }

            if ($meta_title == '') {
                $meta_title = $title;
            }

            if ((int) $this->getRequestValue('page') > 0 && (int) $this->getRequestValue('page') != 1 && 1 == $this->getConfigValue('add_pagenumber_title')) {
                if (0 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (1 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (2 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                }
            }

            $this->template->assign('title', $title);
            $this->template->assign('meta_title', $meta_title);

            if (isset($district_info['description' . $lang_postfix]) && $district_info['description' . $lang_postfix] != '') {
                $this->template->assign('description', $district_info['description' . $lang_postfix]);
            } elseif ($district_info['description'] != '') {
                $this->template->assign('description', $district_info['description']);
            }
            if (isset($district_info['meta_description' . $lang_postfix]) && $district_info['meta_description' . $lang_postfix] != '') {
                $this->template->assign('meta_description', $district_info['meta_description' . $lang_postfix]);
            } elseif ($district_info['meta_description'] != '') {
                $this->template->assign('meta_description', $country_info['meta_description']);
            } else {
                //$this->template->assign('meta_description', $this->getConfigValue('meta_description_main'));
            }
            if (isset($district_info['meta_keywords' . $lang_postfix]) && $district_info['meta_keywords' . $lang_postfix] != '') {
                $this->template->assign('meta_keywords', $district_info['meta_keywords' . $lang_postfix]);
            } elseif ($district_info['meta_keywords'] != '') {
                $this->template->assign('meta_keywords', $district_info['meta_keywords']);
            } else {
                //$this->template->assign('meta_keywords', $this->getConfigValue('meta_keywords_main'));
            }
            $params['district_id'] = (int) $district_info['id'];

            $this->setRequestValue('district_id', (int) $district_info['id']);
            $this->setRequestValue('district_view', $REQUESTURIPATH);
        } elseif ($city_url_catched) {

            if (1 === intval($this->getConfigValue('apps.language.use_langs'))) {
                $curlang = $this->getCurrentLang();
                $lang_postfix = '_' . $curlang;
                if (1 === intval($this->getConfigValue('apps.language.use_default_as_ru')) && $curlang == 'ru') {
                    $lang_postfix = '';
                }
            } else {
                $lang_postfix = '';
            }

            if (isset($city_info['public_title' . $lang_postfix]) && $city_info['public_title' . $lang_postfix] != '') {
                $title = $city_info['public_title' . $lang_postfix];
            } elseif (isset($city_info['public_title']) && $city_info['public_title'] != '') {
                $title = $city_info['public_title'];
            } else {
                $title = $city_info['name'];
                if ($this->getConfigValue('apps.seo.city_title_postfix') != '') {
                    $title .= ' ' . $this->getConfigValue('apps.seo.city_title_postfix');
                }
            }
            if (isset($city_info['meta_title' . $lang_postfix]) && $city_info['meta_title' . $lang_postfix] != '') {
                $meta_title = $city_info['meta_title' . $lang_postfix];
            } elseif ($city_info['meta_title'] != '') {
                $meta_title = $city_info['meta_title'];
            } else {
                $meta_title = $title;
            }


            if ((int) $this->getRequestValue('page') > 0 && (int) $this->getRequestValue('page') != 1 && 1 == $this->getConfigValue('add_pagenumber_title')) {
                $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
            }

            $this->template->assign('title', $title);
            $this->template->assign('meta_title', $meta_title);

            if (isset($city_info['description' . $lang_postfix]) && $city_info['description' . $lang_postfix] != '') {
                $this->template->assign('description', $city_info['description' . $lang_postfix]);
            } elseif ($city_info['description'] != '') {
                $this->template->assign('description', $city_info['description']);
            }
            if (isset($city_info['meta_description' . $lang_postfix]) && $city_info['meta_description' . $lang_postfix] != '') {
                $this->template->assign('meta_description', $city_info['meta_description' . $lang_postfix]);
            } elseif ($city_info['meta_description'] != '') {
                $this->template->assign('meta_description', $city_info['meta_description']);
            } else {
                //$this->template->assign('meta_description', $this->getConfigValue('meta_description_main'));
            }
            if (isset($city_info['meta_keywords' . $lang_postfix]) && $city_info['meta_keywords' . $lang_postfix] != '') {
                $this->template->assign('meta_keywords', $city_info['meta_keywords' . $lang_postfix]);
            } elseif ($city_info['meta_keywords'] != '') {
                $this->template->assign('meta_keywords', $city_info['meta_keywords']);
            } else {
                //$this->template->assign('meta_keywords', $this->getConfigValue('meta_keywords_main'));
            }


            $this->setRequestValue('city_id', (int) $city_info['city_id']);
            $this->setRequestValue('city_view', $REQUESTURIPATH);
        } elseif ($metro_url_catched) {

            if (1 === intval($this->getConfigValue('apps.language.use_langs'))) {
                $curlang = $this->getCurrentLang();
                $lang_postfix = '_' . $curlang;
                if (1 === intval($this->getConfigValue('apps.language.use_default_as_ru')) && $curlang == 'ru') {
                    $lang_postfix = '';
                }
            } else {
                $lang_postfix = '';
            }

            if (isset($metro_info['public_title' . $lang_postfix]) && $metro_info['public_title' . $lang_postfix] != '') {
                $title = $metro_info['public_title' . $lang_postfix];
            } elseif (isset($metro_info['public_title']) && $metro_info['public_title'] != '') {
                $title = $metro_info['public_title'];
            } else {
                $title = $metro_info['name'];
            }
            if (isset($metro_info['meta_title' . $lang_postfix]) && $metro_info['meta_title' . $lang_postfix] != '') {
                $meta_title = $metro_info['meta_title' . $lang_postfix];
            } elseif ($metro_info['meta_title'] != '') {
                $meta_title = $metro_info['meta_title'];
            } else {
                $meta_title = $title;
            }

            if ((int) $this->getRequestValue('page') > 0 && (int) $this->getRequestValue('page') != 1 && 1 == $this->getConfigValue('add_pagenumber_title')) {
                $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
            }

            $this->template->assign('title', $title);
            $this->template->assign('meta_title', $meta_title);

            if (isset($metro_info['description' . $lang_postfix]) && $metro_info['description' . $lang_postfix] != '') {
                $this->template->assign('description', $metro_info['description' . $lang_postfix]);
            } elseif ($metro_info['description'] != '') {
                $this->template->assign('description', $metro_info['description']);
            }
            if (isset($metro_info['meta_description' . $lang_postfix]) && $metro_info['meta_description' . $lang_postfix] != '') {
                $this->template->assign('meta_description', $metro_info['meta_description' . $lang_postfix]);
            } elseif ($metro_info['meta_description'] != '') {
                $this->template->assign('meta_description', $metro_info['meta_description']);
            } else {
                //$this->template->assign('meta_description', $this->getConfigValue('meta_description_main'));
            }
            if (isset($metro_info['meta_keywords' . $lang_postfix]) && $metro_info['meta_keywords' . $lang_postfix] != '') {
                $this->template->assign('meta_keywords', $metro_info['meta_keywords' . $lang_postfix]);
            } elseif ($metro_info['meta_keywords'] != '') {
                $this->template->assign('meta_keywords', $metro_info['meta_keywords']);
            } else {
                //$this->template->assign('meta_keywords', $this->getConfigValue('meta_keywords_main'));
            }


            $this->setRequestValue('metro_id', (int) $metro_info['metro_id']);
            $this->setRequestValue('metro_view', $REQUESTURIPATH);
        } elseif ($region_url_catched) {

            if (1 === intval($this->getConfigValue('apps.language.use_langs'))) {
                $curlang = $this->getCurrentLang();
                $lang_postfix = '_' . $curlang;
                if (1 === intval($this->getConfigValue('apps.language.use_default_as_ru')) && $curlang == 'ru') {
                    $lang_postfix = '';
                }
            } else {
                $lang_postfix = '';
            }

            if (isset($region_info['public_title']) && $region_info['public_title'] != '') {
                $title = $region_info['public_title'];
            } else {
                $title = $region_info['name'];
            }
            if ($region_info['meta_title'] != '') {
                $meta_title = $region_info['meta_title'];
            } else {
                $meta_title = $region_info['name'];
            }

            if ((int) $this->getRequestValue('page') > 0 && (int) $this->getRequestValue('page') != 1 && 1 == $this->getConfigValue('add_pagenumber_title')) {
                if (0 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (1 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (2 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                }
            }

            $this->template->assign('title', $title);
            $this->template->assign('meta_title', $meta_title);

            if ($region_info['description'] != '') {
                $this->template->assign('description', $region_info['description']);
            }
            if ($region_info['meta_description'] != '') {
                $this->template->assign('meta_description', $region_info['meta_description']);
            } else {
                //$this->template->assign('meta_description', $this->getConfigValue('meta_description_main'));
            }
            if ($region_info['meta_keywords'] != '') {
                $this->template->assign('meta_keywords', $region_info['meta_keywords']);
            } else {
                //$this->template->assign('meta_keywords', $this->getConfigValue('meta_keywords_main'));
            }


            $this->setRequestValue('region_id', (int) $region_info['region_id']);
            $this->setRequestValue('region_view', $REQUESTURIPATH);
        } elseif ($complex_url_catched) {
            require_once (SITEBILL_DOCUMENT_ROOT . '/apps/complex/admin/admin.php');
            $complex_admin = new complex_admin();
            $data_model = new Data_Model();
            $complex_data = $complex_admin->data_model;
            $complex_data = $data_model->init_model_data_from_db('complex', 'complex_id', (int) $ar['complex_id'], $complex_data['complex'], true);
            $complex_data['image']['image_array'] = $this->get_image_array('complex', 'complex', 'complex_id', (int) $ar['complex_id']);
            /*
              echo '<pre>';
              print_r($complex_data);
              echo '</pre>';
             */
            $this->template->assign('complex_data', $complex_data);


            if ($complex_info['meta_title'] != '') {
                $title = $complex_info['name'];
                $meta_title = $complex_info['meta_title'];
            } else {
                $title = $meta_title = $complex_info['name'];
            }

            if ((int) $this->getRequestValue('page') > 0 && (int) $this->getRequestValue('page') != 1 && 1 == $this->getConfigValue('add_pagenumber_title')) {
                if (0 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (1 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                } elseif (2 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                    $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                }
            }

            $this->template->assign('title', $title);
            $this->template->assign('meta_title', $meta_title);

            if ($complex_info['description'] != '') {
                $this->template->assign('description', $complex_info['description']);
            }
            if ($complex_info['meta_description'] != '') {
                $this->template->assign('meta_description', $complex_info['meta_description']);
            } else {
                //$this->template->assign('meta_description', $this->getConfigValue('meta_description_main'));
            }
            if ($complex_info['meta_keywords'] != '') {
                $this->template->assign('meta_keywords', $complex_info['meta_keywords']);
            } else {
                //$this->template->assign('meta_keywords', $this->getConfigValue('meta_keywords_main'));
            }


            $this->setRequestValue('complex_id', (int) $ar['complex_id']);
            $this->setRequestValue('complex_view', $REQUESTURIPATH);
        } else {
            $result = $this->request->_detectUrlParams(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));


            if ($result['topic_id']/* && !is_array($result['topic_id']) */) {
                $this->setRequestValue('topic_id', $result['topic_id']);
            }
            if ($result['city_id']) {
                $this->setRequestValue('city_id', $result['city_id']);
            }
            $gorod_name = $result['gorod_name'];






            $url_info = parse_url($_SERVER['REQUEST_URI']);
            if (SITEBILL_MAIN_URL != '') {
                $cmp_url = SITEBILL_MAIN_URL . '/';
            } else {
                $cmp_url = '/';
            }
            if ($this->getRequestValue('country_id') == '' && $this->getRequestValue('city_id') == '' && $this->getRequestValue('topic_id') == '' and ( $url_info['path'] != $cmp_url and $url_info['path'] != $cmp_url . 'index.php' and $url_info['path'] != $cmp_url . 'search/') and $this->getRequestValue('user_id') === NULL) {
                $sapi_name = php_sapi_name();

                if ($sapi_name == 'cgi' || $sapi_name == 'cgi-fcgi') {
                    header('Status: 404 Not Found');
                } else {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
                }
                $this->template->assign('title', Multilanguage::_('L_MESSAGE_PAGE_NOT_FOUND'));
                $this->template->assign('meta_title', Multilanguage::_('L_MESSAGE_PAGE_NOT_FOUND'));
                $this->template->assign('error_message', '<h1>' . Multilanguage::_('L_MESSAGE_PAGE_NOT_FOUND') . '</h1>');
                $this->template->assign('main_file_tpl', 'error_message.tpl');
                //exit();
                //echo 1;
                return false;
            } elseif ((!is_array($result['topic_id']) && $this->getRequestValue('topic_id') > 0 ) or ( $gorod_name != '' and is_array($this->getRequestValue('topic_id')))) {
                if (is_array($this->getRequestValue('topic_id'))) {
                    $tmp_tppc = $this->getRequestValue('topic_id');
                    $topic = $this->getTopicFullInfo($tmp_tppc[0]);
                } else {
                    $topic = $this->getTopicFullInfo($this->getRequestValue('topic_id'));
                }

                if (1 === intval($this->getConfigValue('apps.language.use_langs'))) {
                    $curlang = $this->getCurrentLang();
                    $lang_postfix = '_' . $curlang;
                    if (1 === intval($this->getConfigValue('apps.language.use_default_as_ru')) && $curlang == 'ru') {
                        $lang_postfix = '';
                    }
                }


                if (isset($topic['meta_title' . $lang_postfix]) && $topic['meta_title' . $lang_postfix] != '') {
                    $meta_title = $topic['meta_title' . $lang_postfix];
                } elseif ($topic['meta_title'] != '') {
                    $meta_title = $topic['meta_title'];
                } else {
                    $meta_title = '';
                }

                if (isset($topic['name' . $lang_postfix]) && $topic['name' . $lang_postfix] != '') {
                    $title = $topic['name' . $lang_postfix];
                } else {
                    $title = $topic['name'];
                }

                if (isset($topic['public_title' . $lang_postfix]) && $topic['public_title' . $lang_postfix] != '') {
                    $title = $topic['public_title' . $lang_postfix];
                } elseif (isset($topic['public_title']) && $topic['public_title'] != '') {
                    $title = $topic['public_title'];
                    /* if($meta_title==''){
                      $meta_title=$title;
                      } */
                }

                if ($meta_title == '') {
                    $meta_title = $title;
                }

                if (isset($topic['description' . $lang_postfix]) && $topic['description' . $lang_postfix] != '') {
                    $this->template->assign('description', $topic['description' . $lang_postfix]);
                } elseif ($topic['description'] != '') {
                    $this->template->assign('description', $topic['description']);
                }
                if (isset($topic['meta_description' . $lang_postfix]) && $topic['meta_description' . $lang_postfix] != '') {
                    $this->template->assign('meta_description', $topic['meta_description' . $lang_postfix]);
                } elseif ($topic['meta_description'] != '') {
                    $this->template->assign('meta_description', $topic['meta_description']);
                }
                if (isset($topic['meta_keywords' . $lang_postfix]) && $topic['meta_keywords' . $lang_postfix] != '') {
                    $this->template->assign('meta_keywords', $topic['meta_keywords' . $lang_postfix]);
                } elseif ($topic['meta_keywords'] != '') {
                    $this->template->assign('meta_keywords', $topic['meta_keywords']);
                }
                if ($gorod_name) {
                    $title .= ' - ' . $gorod_name;
                }

                if ((int) $this->getRequestValue('page') > 0 && (int) $this->getRequestValue('page') != 1 && 1 == $this->getConfigValue('add_pagenumber_title')) {
                    if (0 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                        $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    } elseif (1 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                        $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    } elseif (2 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                        $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                        $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    }
                }
                $this->template->assign('title', $title);
                $this->template->assign('meta_title', $meta_title);
            } else {
                if ($this->getConfigValue('meta_title_main') != '') {
                    $title = $this->getConfigValue('site_title');
                    $meta_title = $this->getConfigValue('meta_title_main');
                } else {
                    $title = $meta_title = $this->getConfigValue('site_title');
                }
                //$title = ($this->getConfigValue('meta_title_main')!='' ? $this->getConfigValue('meta_title_main') : $this->getConfigValue('site_title'));
                if ((int) $this->getRequestValue('page') > 0 && (int) $this->getRequestValue('page') != 1 && 1 == $this->getConfigValue('add_pagenumber_title')) {
                    if (0 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                        $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    } elseif (1 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                        $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    } elseif (2 == (int) $this->getConfigValue('add_pagenumber_title_place')) {
                        $title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                        $meta_title .= ' [' . Multilanguage::_('L_PAGE') . ' ' . $this->getRequestValue('page') . ']';
                    }
                }
                if (preg_match('/user(\d+).html/', $_SERVER['REQUEST_URI'], $matches)) {
                    $user_id = $matches[1];
                    $fio = '';
                    if (0 !== (int) $user_id) {
                        $DBC = DBC::getInstance();
                        $query = 'SELECT fio FROM ' . DB_PREFIX . '_user WHERE user_id=? LIMIT 1';
                        $stmt = $DBC->query($query, array((int) $user_id));
                        if ($stmt) {
                            $ar = $DBC->fetch($stmt);
                            $fio = $ar['fio'];
                        }
                    }
                    $title = Multilanguage::_('AGENT_ADS', 'system') . ' ' . $fio;
                    $meta_title = $title;
                } elseif ((int) $this->getRequestValue('user_id') != 0) {
                    $user_id = (int) $this->getRequestValue('user_id');
                    $fio = '';
                    if (0 !== (int) $user_id) {
                        $DBC = DBC::getInstance();
                        $query = 'SELECT fio FROM ' . DB_PREFIX . '_user WHERE user_id=? LIMIT 1';
                        $stmt = $DBC->query($query, array((int) $user_id));
                        if ($stmt) {
                            $ar = $DBC->fetch($stmt);
                            $fio = $ar['fio'];
                        }
                    }

                    $title = Multilanguage::_('AGENT_ADS', 'system') . ' ' . $fio;
                    $meta_title = $title;
                }
                //$meta_title=$title;
                if (!$this->lock_title) {
                    $this->template->assign('title', $title);
                }
                $this->template->assign('meta_title', $meta_title);
                $this->template->assign('meta_description', $this->getConfigValue('meta_description_main'));
                $this->template->assign('meta_keywords', $this->getConfigValue('meta_keywords_main'));
            }
        }
    }

}
