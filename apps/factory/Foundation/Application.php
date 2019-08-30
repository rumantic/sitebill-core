<?php
namespace factory\Foundation;

use complex_admin;
use Data_Model;
use DBC;
use factory\Foundation\AbstractKernel;
use Multilanguage;
use predefinedlinks_admin;
use system\lib\frontend\grid\Grid_Constructor;
use system\lib\SiteBill;
use factory\Foundation\MetaTags;

class Application {
    /**
     * @var \system\lib\template\Template
     */
    private $template;
    /**
     * @var Request
     */
    private $request;

    function __construct() {

    }

    public function make () {
        $sitebill = new SiteBill();
        $this->template = $sitebill->get_template_instance();

        $this->request = new Request();
        $this->grid_adv();

    }

    function template_instance () {
        return $this->template;
    }

    function request_instance () {
        return $this->request;
    }

    function getRequestValue ($key, $type = '', $from = '') {
        return $this->request->getRequestValue($key, $type, $from);
    }

    function getConfigValue ($key) {
        return $this->request->getConfigValue($key);
    }

    public function sendResponse () {
        $this->template->display('main.tpl');

    }
    /**
     * Grid adv
     * @param void
     * @return string
     */
    function grid_adv($params = array()) {
        /* возможны вариант отдачи списка в виде эксель-таблицы */
        /* $to_excell=false;
          if(isset($params['format']) && $params['format']='excell'){
          $to_excell=true;
          unset($params['format']);
          } */

        $any_url_catched = false;

        $country_url_catched = false;
        $find_url_catched = false;
        $city_url_catched = false;
        $metro_url_catched = false;
        $region_url_catched = false;
        $district_url_catched = false;
        $predefined_url_catched = false;
        $route_catched = false;
        $complex_url_catched = false;
        $REQUESTURIPATH = SiteBill::getClearRequestURI();

        $meta_tags = new \factory\Foundation\MetaTags($this->request_instance(), $this->template_instance());
        $meta_tags->compile($REQUESTURIPATH, $any_url_catched);

        $grid_constructor = new Grid_Constructor($this->template_instance());

        $this->setGridViewType();


        if ($route_catched) {

        } elseif ($predefined_url_catched) {

        } elseif ($country_url_catched) {

        } elseif ($district_url_catched) {

        } elseif ($city_url_catched) {
            if (method_exists($this, 'cityFrontPage')) {
                return $this->cityFrontPage($city_info);
            }
        } elseif ($region_url_catched) {

        } elseif ($complex_url_catched) {

        } else {

        }

        $params_r = $this->request_instance()->gatherRequestParams();
        if (!empty($params)) {
            $params = array_merge($params, $params_r);
        } else {
            $params = $params_r;
        }

        /* if($to_excell){
          $params['no_portions']=1;
          $data=$grid_constructor->get_sitebill_adv_core($params, false, false, false, false);
          return $this->getRealtyListAsExcell($data);
          } */

        $grid_constructor->main($params);

        return '';
    }
    protected function setGridViewType() {

        if (in_array($this->getRequestValue('grid_type'), array('thumbs', 'list'))) {
            $_SESSION['grid_type'] = $this->getRequestValue('grid_type');
        } else {
            if (!isset($_SESSION['grid_type'])) {
                if ($this->getConfigValue('grid_type') != '') {
                    $_SESSION['grid_type'] = $this->getConfigValue('grid_type');
                } else {
                    $_SESSION['grid_type'] = 'list';
                }
            }
        }
    }

}
