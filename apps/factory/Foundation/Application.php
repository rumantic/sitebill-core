<?php
namespace factory\Foundation;

use complex_admin;
use Data_Model;
use DBC;
use factory\Foundation\AbstractKernel;
use Multilanguage;
use predefinedlinks_admin;
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

        $grid_constructor = $this->_getGridConstructor();

        //$SF=Sitebill_Registry::getInstance();
        //$SF->clearFeedback('catched_route');
        //$SF->clearFeedback('catched_route_params');


        /* $Sitebill_Registry=Sitebill_Registry::getInstance();
          if(1==(int)$Sitebill_Registry->getFeedback('route_catched')){
          $route_catched=true;
          }else{
          $route_catched=false;
          } */



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

        $params_r = $this->gatherRequestParams();
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

}
