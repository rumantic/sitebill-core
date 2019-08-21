<?php
namespace api\classes;
use system\lib\SiteBill;
use system\lib\system\user\Login;
use api\classes\API_Response;
use api\classes\API_Request;
use system\lib\admin\data\Data_Manager;
use system\lib\system\DBC;

defined('SITEBILL_DOCUMENT_ROOT') or die('Restricted access');
/**
 * API Common class
 * @author Kondin Dmitriy <kondin@etown.ru> http://www.sitebill.ru
 */
class API_Common extends SiteBill {
    protected $request; // API_Request

    /**
     * Constructor
     */
    function __construct() {
        $this->request = new API_Request;
        $Login = new Login();
        $Login->checkLogin('', '', true, $this->getRequestValue('session_key'));
        $_POST = $this->request->dump();
        
        //$this->writeLog(__METHOD__.', request = <pre>'. var_export($this->request->dump(), true).'</pre>');
        
    }
    
    function getRequestValue($key, $type = '', $from = '') {
        if ( $this->request->get($key) != null ) {
            return $this->request->get($key);
        }
        return parent::getRequestValue($key, $type, $from);
    }

    function main() {
        $do = $this->getRequestValue('do');
        $action = '_' . $do;
        if (!method_exists($this, $action)) {
            $action = '_default';
        }

        $rs .= $this->$action();
        return $rs;
    }

    function _default() {
        return $this->request_failed('method not defined');
    }

    function force_get_session_key() {
        //Сначала из REQUEST
        //И затем из php://input
        if ($this->get_session_key()) {
            return $this->get_session_key();
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
            if ( $data['session_key'] != '' ) {
                return $data['session_key'];
            }
        }
        return false;
    }

    function get_my_user_id() {
        if ($this->getSessionUserId()) {
            return $this->getSessionUserId();
        }
        $session_key = $this->request->get('session_key');
        if ( $session_key == '' ) {
            $session_key = $this->force_get_session_key();
        }
        $DBC = DBC::getInstance();
        $query = 'SELECT user_id FROM ' . DB_PREFIX . '_oauth WHERE session_key=?';
        $stmt = $DBC->query($query, array($session_key));
        if ($stmt) {
            $ar = $DBC->fetch($stmt);
            if ($ar['user_id'] > 0) {
                return $ar['user_id'];
            }
        }
        return false;
    }

    function request_failed($message) {
        $response = array('state'=>'error','error' => $message);
        return $this->json_string($response);
    }
    
    function request_success($message) {
        $response = array('state'=>'success','message' => $message);
        return $this->json_string($response);
    }
    

    function json_string($in_array) {
        return json_encode($in_array);
    }
    
    function init_custom_model_object($model_name) {
        switch ( $model_name ) {
            case 'city':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/city/city_manager.php');
                $city_manager = new City_Manager();
                return $city_manager;
            break;
        
            case 'component':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/component/component_manager.php');
                $component_manager = new Component_Manager;
                return $component_manager;
            break;
        
            case 'country':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/country/country_manager.php');
                $country_manager = new Country_Manager;
                return $country_manager;
            break;
        
            case 'data':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/data/data_manager.php');
                $data_manager = new Data_Manager;
                return $data_manager;
            break;
        
            case 'district':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/district/district_manager.php');
                $district_manager = new District_Manager;
                return $district_manager;
            break;
        
            case 'function':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/function/function_manager.php');
                $function_manager = new Function_Manager;
                return $function_manager;
            break;
        
            case 'group':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/group/group_manager.php');
                $group_manager = new Group_Manager;
                return $group_manager;
            break;
        
            case 'menu':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/menu/menu_manager.php');
                $menu_manager = new Menu_Manager;
                return $menu_manager;
            break;

            case 'page':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/page/admin/admin.php');
                $page_admin = new page_admin();
                return $page_admin;
            break;
        
        
            case 'metro':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/metro/metro_manager.php');
                $metro_manager = new Metro_Manager();
                return $metro_manager;
            break;
        
            case 'region':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/region/region_manager.php');
                $region_manager = new Region_Manager();
                return $region_manager;
            break;
        
            case 'street':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/street/street_manager.php');
                $street_manager = new Street_Manager();
                return $street_manager;
            break;
        
            case 'user':
                require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/users/user_object_manager.php');
                $user_object_manager = new User_Object_Manager();
                return $user_object_manager;
            break;
        
        
        }
        
        $DBC = DBC::getInstance();
        $query = "SELECT name FROM " . DB_PREFIX . "_table WHERE name=?";
        $stmt = $DBC->query($query, array($model_name));
        if (!$stmt) {
            $this->riseError('model not defined');
            return false;
        }

        $ar = $DBC->fetch($stmt);
        $model_name = $ar['name'];
        if ($model_name != '') {
            require_once (SITEBILL_DOCUMENT_ROOT . '/apps/customentity/admin/admin.php');
            $customentity_admin = new customentity_admin();
            $customentity_admin->custom_construct($model_name);
            return $customentity_admin;
        }
        $this->riseError('model not defined');
        return false;
    }
}
