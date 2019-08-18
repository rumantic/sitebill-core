<?php

/**
 * SiteBill parent class
 * @author Kondin Dmitriy <kondin@etown.ru>
 */
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}
if (!defined('DB_HOST')) {
    define('DB_HOST', $__server);
}
if (!defined('DB_PORT')) {
    define('DB_PORT', $__db_port);
}
if (!defined('DB_BASE')) {
    define('DB_BASE', $__db);
}
if (!defined('DB_USER')) {
    define('DB_USER', $__user);
}
if (!defined('DB_PREFIX')) {
    define('DB_PREFIX', $__db_prefix);
}
if (!defined('DB_PASS')) {
    define('DB_PASS', $__password);
}
if (!defined('DB_DSN')) {
    if (defined(DB_PORT) && DB_PORT != '') {
        define('DB_DSN', 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_BASE);
    } else {
        define('DB_DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_BASE);
    }
}

if (!defined('DB_ENCODING')) {
    define('DB_ENCODING', 'cp1251');
}

if (!defined('SITE_ENCODING')) {
    define('SITE_ENCODING', 'windows-1251');
}

if (!defined('DEBUG_ENABLED')) {
    define('DEBUG_ENABLED', false);
}

if (!defined('LOG_ENABLED')) {
    define('LOG_ENABLED', false);
}

if (!defined('UPLOADIFY_TABLE')) {
    define('UPLOADIFY_TABLE', DB_PREFIX . '_uploadify');
}

if (!defined('IMAGE_TABLE')) {
    define('IMAGE_TABLE', DB_PREFIX . '_image');
}

if (!defined('MEDIA_FOLDER')) {
    define('MEDIA_FOLDER', SITEBILL_DOCUMENT_ROOT . '/img/data');
}



if (!defined('ESTATE_FOLDER')) {
    define('ESTATE_FOLDER', $folder);
}
if (!defined('SITEBILL_DOCUMENT_ROOT')) {
    define('SITEBILL_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . ESTATE_FOLDER);
}

if (!defined('SITEBILL_APPS_DIR')) {
    define('SITEBILL_APPS_DIR', SITEBILL_DOCUMENT_ROOT . '/apps');
}
if (!defined('SITEBILL_MAIN_URL')) {
    define('SITEBILL_MAIN_URL', ESTATE_FOLDER);
}
/* if(!defined('SITEBILL_MAIN_FULLURL')){
  define('SITEBILL_MAIN_FULLURL','http://'.$_SERVER['HTTP_HOST'].ESTATE_FOLDER);
  } */
/*
  if(isset($_GET['run_debug'])){
  define('DEBUG_ENABLED',true);
  unset($_GET['run_debug']);
  }
 */
//require_once(SITEBILL_DOCUMENT_ROOT.'/apps/system/lib/system/sitebill_application.php');

/* $_SESSION['csrftoken'] = md5(uniqid(mt_rand() . microtime()));
  if($_SESSION['csrfsecret']==''){
  $_SESSION['csrfsecret']=md5(uniqid(mt_rand() . microtime()));
  } */

/*
  $salt=substr(md5(time().rand(100,999)), 0, 6);
  $token = $salt.":".MD5($salt.":".$_SESSION['skey']);
  setcookie('CSRF-TOKEN', $token, time()+3600, '/', Sitebill::$_cookiedomain); */
require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/sitebill_autoload.php';


require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/debugger.class.php';
require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/logger.class.php';
require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/dbc.php';
require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/sconfig.php';
require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/sitebill_datetime.php';



//require_once SITEBILL_DOCUMENT_ROOT.'/apps/system/lib/system/sitebill_router.php';
//require_once SITEBILL_DOCUMENT_ROOT.'/apps/system/lib/system/sitebill_user.php';

$SConfig = SConfig::getInstance();
if ('' != $SConfig->getConfigValue('default_timezone')) {
    ini_set('date.timezone', $SConfig->getConfigValue('default_timezone'));
    //date_default_timezone_set($SConfig->getConfigValue('default_timezone'));
}



require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/sitebill_registry.php');
require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/multilanguage/multilanguage.class.php';
/* if(isset($_REQUEST['_lang'])){
  $_SESSION['_lang']=$_REQUEST['_lang'];
  }else{
  if(!isset($_SESSION['_lang'])){
  $_SESSION['_lang']=$SConfig->getConfigValue('apps.language.default_lang_code');
  }
  } */
//Multilanguage::start('frontend', $_SESSION['_lang']);
//Sitebill_User::getInstance();

if (isset($_REQUEST['search'])) {
    $_SESSION['rem_page'] = 1;
}
if (isset($_REQUEST['page'])) {
    $_SESSION['rem_page'] = $_REQUEST['page'];
} elseif (!isset($_SESSION['rem_page'])) {
    $_SESSION['rem_page'] = 1;
}
$_POST['page'] = $_SESSION['rem_page'];

//Sitebill::setLangSession();
//Sitebill::parseLocalSettings();
//Sitebill::initLocalComponents();
/*
  if(!isset($_SESSION['Sitebill_User']) || !is_array($_SESSION['Sitebill_User'])){
  $_SESSION['Sitebill_User']=array();
  $_SESSION['Sitebill_User']['name']='';
  $_SESSION['Sitebill_User']['group_id']=0;
  $_SESSION['Sitebill_User']['group_name']='Гость';
  $_SESSION['Sitebill_User']['login']='';
  $_SESSION['Sitebill_User']['user_id']=0;
  $_SESSION['Sitebill_User']['group_system_name']='guest';
  }
 */
class SiteBill {
    /**
     * @var bool
     */
    private static $template_inited = false;
    private static $smarty_instance;

    /**
     * Error message
     */
    var $error_message = false;
    var $uploadify_dir = '/cache/upl/';
    var $storage_dir = '/img/data/';
    protected static $config_loaded = false;
    protected static $config_array = array();
    /* protected static $local_config = false; */
    private $external_uploadify_image_array = false;
    protected static $storage = array();
    protected static $Heaps = array();

    /* Container for local site settings from settings.ini.php */
    protected static $localSettings = false;
    public static $_grid_constructor_local = null;
    public static $_realty_viewer_local = null;
    protected $_grid_constructor = null;
    public static $_cookiedomain = '';
    public static $_trslashes = null;

    const MEDIA_SAVE_FOLDER = 1;
    
    public static $_csrf_token = '';

    /**
     * Constructor
     */
    function SiteBill() {
        Multilanguage::appendAppDictionary('system');
        require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/template/template.php';
        if (!self::$localSettings) {
            $this->parseLocalSettings();
            $this->initLocalComponents();
        }
        if($this->_grid_constructor === null){
            $this->_grid_constructor = self::$_grid_constructor_local;
        }
        if ( !self::$template_inited ) {
            $this->init_template_engine();
        }


        //$this->db = new Db( $__server, $__db, $__user, $__password );
        Sitebill_Datetime::setDateFormat($this->getConfigValue('date_format'));

        self::setLangSession();

        require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/db/mysql_db_emulator.php';
        $this->db = new Mysql_DB_Emulator();
        $this->load_hooks();
    }

    public function init_template_engine () {
        global $smarty;
        if (!isset($smarty->registered_plugins['function']['_e'])) {
            if ( function_exists('_translate') ) {
                //$smarty->register_function("_e", "_translate");
            }
        }


        $this->template = new Template();
        if ($this->isDemo()) {
            $this->template->assign('show_demo_banners', '1');
        }

        $this->template->assign('estate_folder', SITEBILL_MAIN_URL);
        $this->template->assert('theme_folder', SITEBILL_MAIN_URL . '/template/frontend/' . $this->getConfigValue('theme'));
        $this->template->assign('bootstrap_version', trim($this->getConfigValue('bootstrap_version')));

        $params_str = 'var SitebillVars={};';
        $params_str .= 'SitebillVars.resInc=\'' . SITEBILL_MAIN_URL . '\';';
        $params_str .= 'SitebillVars.linkPath=\'' . SITEBILL_MAIN_URL . '\';';
        $params_str .= 'SitebillVars.ajaxPath=\'' . SITEBILL_MAIN_URL . '/js/ajax.php\';';
        $params_str = '<script>' . $params_str . '</script>';
        $this->template->assign('SitebillVars', $params_str);
        if (defined('ADMIN_NO_MAP')) {
            $this->template->assign('ADMIN_NO_MAP_PROVIDERS', '1');
        } else {
            $this->template->assign('ADMIN_NO_MAP_PROVIDERS', '0');
        }
        if (defined('ADMIN_NO_NANOAPI')) {
            $this->template->assign('ADMIN_NO_NANOAPI', '1');
        } else {
            $this->template->assign('ADMIN_NO_NANOAPI', '0');
        }
        if (1 == $this->getConfigValue('use_google_map')) {
            $this->template->assert('map_type', 'google');
            //$this->template->assert('map_type', 'leaflet_osm');
        } else {
            $this->template->assert('map_type', 'yandex');
        }
        $this->template->assert('estate_folder', SITEBILL_MAIN_URL);
        self::$template_inited = true;
    }
    
    public function checkCSRFToken($csrf_token){
        list($valid_thru, $token) = explode(':', $csrf_token);
        $n = $valid_thru.':'.base64_encode(
            hash_hmac(
                'sha256', 
                $valid_thru . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $_SESSION['key'],
                $this->getConfigValue('csrf_salt'),
                true
            )
        );
        if($n === $csrf_token && $valid_thru >= time()){
            return true;
        }        
        return false;
    }
    
    public function generateCSRFToken($len = 40){
        $array = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $p = array();
        for($i=1; $i<=$len; $i++){
            shuffle($array);
            $p[] = $array[0];
        }
        return implode('', $p);
    }
    
    function load_hooks() {
        if (file_exists(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . '/hooks' . '/hooks.php')) {
            include_once (SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . '/hooks' . '/hooks.php');
        }
    }

    public static function genPassword($len = 8) {
        $array = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '@', '#', '%', '&', '?', '!');
        shuffle($array);
        $p = array_slice($array, 0, $len);
        return implode('', $p);
    }

    public function getCurrentLang() {
        return $_SESSION['_lang'];
    }

    public function getUserHREF($rid, $external = false, $params = array()) {
        $parts = array();
        
        if(false===$this->getConfigValue('apps.seo.user_html_end')){
            $use_html_end = true;
        }else{
            $use_html_end = (1 === intval($this->getConfigValue('apps.seo.user_html_end')) ? true : false);
        }

        if(false===$this->getConfigValue('apps.seo.user_slash_divider')){
            $use_slash_divider = false;
        }else{
            $use_slash_divider = (1 === intval($this->getConfigValue('apps.seo.user_slash_divider')) ? true : false);
        }
        



        if (trim($this->getConfigValue('apps.seo.user_alias')) != '') {
            $user_alias = trim($this->getConfigValue('apps.seo.user_alias'));
        } else {
            $user_alias = 'user';
        }

        if ($use_slash_divider) {
            $user_alias = $user_alias . '/' . $rid;
        } else {
            $user_alias = $user_alias . $rid;
        }

        if ($use_html_end) {
            $user_alias = $user_alias . '.html';
        } else {
            $user_alias = $user_alias . self::$_trslashes;
        }


        $href = '';
        if ($external) {
            $href = $this->getServerFullUrl() . '/' . $user_alias;
        } else {
            $href = SITEBILL_MAIN_URL . '/' . $user_alias;
        }
        return $href;
    }

    public function getRealtyHREF($rid, $external = false, $params = array()) {
        $parts = array();

        if (isset($params['topic_id'])) {
            $topic_id = intval($params['topic_id']);
        } else {
            $topic_id = 0;
        }

        if (isset($params['alias'])) {
            $alias = $params['alias'];
        } else {
            $alias = '';
        }

        if (trim($this->getConfigValue('apps.seo.realty_alias')) != '') {
            $realty_alias = trim($this->getConfigValue('apps.seo.realty_alias'));
        } else {
            $realty_alias = 'realty';
        }


        /* $trailing_slashe = '/';
          if (1 == (int) $this->getConfigValue('apps.seo.no_trailing_slashes')) {
          $trailing_slashe = '';
          } */

        $trailing_slashe = self::$_trslashes;

        if (1 == $this->getConfigValue('apps.seo.level_enable')) {
            require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/structure/structure_manager.php');
            $Structure_Manager = new Structure_Manager();
            $category_structure = $Structure_Manager->loadCategoryStructure();
            if ($category_structure['catalog'][$topic_id]['url'] != '') {
                $parts[] = $category_structure['catalog'][$topic_id]['url'];
            }
        }

        if (1 == $this->getConfigValue('apps.seo.data_alias_enable') && $alias != '') {
            $parts[] = $alias;
        } elseif (1 == $this->getConfigValue('apps.seo.html_prefix_enable')) {
            $trailing_slashe = '';
            $parts[] = $realty_alias . $rid . '.html';
        } else {
            $parts[] = $realty_alias . $rid;
        }
        $href = '';
        if ($external) {
            $href = implode('/', $parts);
            if ($href != '') {
                $href .= $trailing_slashe;
            }
            $href = $this->getServerFullUrl() . '/' . $href;
        } else {
            array_unshift($parts, SITEBILL_MAIN_URL);
            $href = implode('/', $parts);
            if ($href != '') {
                $href .= $trailing_slashe;
            }
        }
        return $href;
    }

    /*
     * return nonslashed full net url
     */

    public function getServerFullUrl($domain_only = false) {
        return (1 === (int) $this->getConfigValue('work_on_https') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . (!$domain_only ? SITEBILL_MAIN_URL : '');
    }

    protected function initLocalComponents() {
        $SConf = SConfig::getInstance();
        //var_dump($SConf->getConfigValue('theme'));
        if (self::$_grid_constructor_local === null) {
            if (self::$localSettings && isset(self::$localSettings['GridConstructor']) && file_exists(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . self::$localSettings['GridConstructor']['path'])) {
                require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/sitebill_krascap.php';
                require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/frontend/grid/grid_constructor.php';
                require_once SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $SConf->getConfigValue('theme') . self::$localSettings['GridConstructor']['path'];
                $gcname = self::$localSettings['GridConstructor']['name'];
                self::$_grid_constructor_local = new $gcname();
            } elseif (1 == intval($SConf->getConfigValue('classic_local_grid')) && file_exists(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . '/main/grid/local_grid_constructor.php')) {
                require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/sitebill_krascap.php';
                require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/frontend/grid/grid_constructor.php';
                require_once SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $SConf->getConfigValue('theme') . '/main/grid/local_grid_constructor.php';
                $gcname = 'Local_Grid_Constructor';
                self::$_grid_constructor_local = new $gcname();
            } else {
                require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/frontend/grid/grid_constructor.php';
                self::$_grid_constructor_local = new Grid_Constructor();
            }
        }
        if (self::$_realty_viewer_local === null) {
            if (self::$localSettings && isset(self::$localSettings['RealtyView']) && file_exists(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . self::$localSettings['RealtyView']['path'])) {
                require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/sitebill_krascap.php';
                require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/frontend/view/kvartira_view.php');
                require_once SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $SConf->getConfigValue('theme') . self::$localSettings['RealtyView']['path'];
                $gcname = self::$localSettings['RealtyView']['name'];
                self::$_realty_viewer_local = new $gcname();
            } elseif (1 == intval($SConf->getConfigValue('classic_local_view')) && file_exists(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $SConf->getConfigValue('theme') . '/main/view/local_kvartira_view.php')) {
                require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/sitebill_krascap.php';
                require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/frontend/view/kvartira_view.php');
                require_once SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $SConf->getConfigValue('theme') . '/main/view/local_kvartira_view.php';
                $gcname = 'Local_Kvartira_View';
                self::$_realty_viewer_local = new $gcname();
            } else {
                require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/frontend/view/kvartira_view.php');
                self::$_realty_viewer_local = new Kvartira_View();
            }
        }
        if (1 === intval($SConf->getConfigValue('set_cookie_subdomenal'))) {
            $cd = trim($SConf->getConfigValue('core_domain'));
            if ($cd != '') {
                self::$_cookiedomain = '.' . $cd;
            }
            //self::$_cookiedomain='.'.$SConf->getConfigValue('core_domain');
        }/* else{
          self::$_cookiedomain='.'.$_SERVER['HTTP_HOST'];
          } */
        //self::$_cookiedomain='';

        if (is_null(self::$_trslashes)) {
            if (1 == intval($SConf->getConfigValue('apps.seo.no_trailing_slashes'))) {
                self::$_trslashes = '';
            } else {
                self::$_trslashes = '/';
            }
        }
    }

    /* function SiteBill() {
      //echo 'SiteBill<br>';
      } */

    protected function parseLocalSettings() {
        //var_dump(self::$localSettings);
        if (!self::$localSettings) {
            if ($settings = parse_ini_file(SITEBILL_DOCUMENT_ROOT . '/settings.ini.php', true)) {
                self::$localSettings = $settings;
            } else {
                self::$localSettings = array();
            }
        }
    }

    protected function _setGridConstructor($newGridConstructor) {
        $this->_grid_constructor = $newGridConstructor;
        self::$_grid_constructor_local = $newGridConstructor;
    }

    public function _getGridConstructor() {
        return self::$_grid_constructor_local;
    }

    public function _getRealtyViewer() {
        return self::$_realty_viewer_local;
    }

    /*
      public function USER_isUserAuthorized(){
      if(isset($_SESSION['Sitebill_User']['user_id']) && (int)$_SESSION['Sitebill_User']['user_id']>0){
      return true;
      }
      return false;
      }

      public function USER_getUserId(){
      if(isset($_SESSION['Sitebill_User']['user_id']) && (int)$_SESSION['Sitebill_User']['user_id']>0){
      return $_SESSION['Sitebill_User']['user_id'];
      }
      return 0;
      }

      public function USER_logoutUser(){
      if(isset($_SESSION['Sitebill_User'])){
      unset($_SESSION['Sitebill_User']);
      }
      }
     */

    /**
     * @param timestamp $date
     * @return timestamp
     */
    static function addMonthToDate($date) {
        $now_day = date('j', $date);
        $now_month = date('n', $date);
        $now_year = date('Y', $date);
        $now_month_days = date('t', $date);
        $time = date('H:i:s', $date);

        $next_year = $now_year;
        $next_month = $now_month + 1;
        if ($next_month > 12) {
            $next_month = 1;
            $next_year += 1;
        }

        $next_month_days = date('t', strtotime($next_year . '-' . ($next_month < 10 ? '0' . $next_month : $next_month) . '-01'));

        if ($now_day <= $next_month_days) {
            $next_day = $now_day;
        } elseif ($now_day == $now_month_days) {
            $next_day = $next_month_days;
        } else {
            $next_day = $next_month_days;
        }
        return strtotime($next_year . '-' . ($next_month < 10 ? '0' . $next_month : $next_month) . '-' . ($next_day < 10 ? '0' . $next_day : $next_day) . ' ' . $time);
    }

    static function getAttachments($object_type, $object_id) {
        $attachments = array();
        if ((int) $object_id == 0 || $object_type == '') {
            return $attachments;
        }
        $DBC = DBC::getInstance();
        $stmt = $DBC->query('SELECT * FROM ' . DB_PREFIX . '_attachment WHERE object_type=? AND object_id=?', array($object_type, $object_id));
        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                $attachments[] = $ar;
            }
        }
        return $attachments;
    }

    static function appendAttachments($object_type, $object_id, $attachments) {
        if (count($attachments) > 0) {
            $DBC = DBC::getInstance();
            $q = 'INSERT INTO ' . DB_PREFIX . '_attachment (file_name, object_id, object_type) VALUES (?,?,?)';
            foreach ($attachments as $attachment) {
                if (file_exists(SITEBILL_DOCUMENT_ROOT . '/cache/upl/' . $attachment)) {
                    copy(SITEBILL_DOCUMENT_ROOT . '/cache/upl/' . $attachment, SITEBILL_DOCUMENT_ROOT . '/attachments/' . $attachment);
                    unlink(SITEBILL_DOCUMENT_ROOT . '/cache/upl/' . $attachment);
                    if (file_exists(SITEBILL_DOCUMENT_ROOT . '/cache/upl/thumbnail/' . $attachment)) {
                        unlink(SITEBILL_DOCUMENT_ROOT . '/cache/upl/thumbnail/' . $attachment);
                    }
                    $DBC->query($q, array($attachment, $object_id, $object_type));
                }
            }
        }
    }

    function escape($text) {
        if (get_magic_quotes_gpc()) {
            $text = stripcslashes($text);
        }
        return $text;
    }

    public function getAdminTplFolder() {
        return SITEBILL_DOCUMENT_ROOT . '/apps/admin/admin/template1';
    }

    /**
     * Get breadcrumbs
     * @param array $items
     * @return string
     */
    function get_breadcrumbs($items) {
        if (count($items) > 0) {
            $this->template->assert('breadcrumbs_array', $items);
            return implode(' / ', $items);
        }
        return '';
    }

    function get_ajax_functions() {
        $rs = '<script type="text/javascript" src="' . SITEBILL_MAIN_URL . '/apps/system/js/refresher.functions.js"></script>';
        return $rs;
    }

    /**
     * Get apps template full path
     * @param string $apps_name
     * @param string $theme
     * @param string $template_value
     * @return boolean
     */
    function get_apps_template_full_path($apps_name, $theme, $template_value) {
        if (!file_exists(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $theme . '/' . $apps_name . '/' . $template_value)) {
            if (file_exists(SITEBILL_DOCUMENT_ROOT . '/apps/' . $apps_name . '/site/template/' . $template_value)) {
                return SITEBILL_DOCUMENT_ROOT . '/apps/' . $apps_name . '/site/template/' . $template_value;
            } else {
                echo Multilanguage::_('L_FILE') . " " . SITEBILL_DOCUMENT_ROOT . '/apps/' . $apps_name . '/site/template/' . $template_value . ' не найден';
                exit;
            }
        } else {
            return SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $theme . '/' . $apps_name . '/' . $template_value;
        }
    }

    /**
     * Get page by URI
     * @param string $uri uri
     * @return array
     */
    function getPageByURI($uri) {
        $DBC = DBC::getInstance();
        $query = 'SELECT * FROM ' . DB_PREFIX . '_page WHERE uri=? LIMIT 1';
        $uri = str_replace('/', '', $uri);
        $stmt = $DBC->query($query, array($uri));
        if ($stmt) {
            $ar = $DBC->fetch($stmt);
            if ($ar['page_id'] > 0) {
                return $ar;
            }
        }
        return false;
    }

    /**
     * Get session key
     * @param void
     * @return string
     */
    function get_session_key() {
        return $_SESSION['key'];
    }

    /**
     * Delete session by key
     * @param string $session_key
     * @return void
     */
    function delete_session_key($session_key) {
        $DBC = DBC::getInstance();
        $query = "DELETE FROM " . DB_PREFIX . "_session WHERE session_key=?";
        $stmt = $DBC->query($query, array((string) $session_key));
        return $_SESSION['key'];
    }

    /**
     * Get session user ID
     * @param void
     * @return int
     */
    function getSessionUserId() {
        $key = (isset($_SESSION['key']) ? $_SESSION['key'] : '');
        if (self::$Heaps['session']['user_id'] != '') {
            return self::$Heaps['session']['user_id'];
        }
        if ($key != '') {
            $DBC = DBC::getInstance();
            $query = "SELECT user_id FROM " . DB_PREFIX . "_session WHERE session_key=? LIMIT 1";
            $stmt = $DBC->query($query, array((string) $key));
            if ($stmt) {
                $ar = $DBC->fetch($stmt);
                $user_id = $ar['user_id'];
                if ($user_id != '' and $user_id != 0) {
                    $this->user_id = $user_id;
                    self::$Heaps['session']['user_id'] = $user_id;
                    //$init->setUserId($user_id);
                    return $user_id;
                } else {
                    $this->user_id = 0;
                    return 0;
                }
            }
        }
        $this->user_id = 0;
        return 0;
    }

    /**
     * Load uploadify images
     * @param string $session_code session code
     * @return array
     */
    function load_uploadify_images($session_code = '', $element_name = '') {
        $ra = array();

        $DBC = DBC::getInstance();
        if ($element_name == '') {
            $query = 'SELECT * FROM ' . UPLOADIFY_TABLE . ' WHERE `session_code`=? AND `element`=? ORDER BY `uploadify_id`';
            $stmt = $DBC->query($query, array((string) $session_code, ''));
        } else {
            $query = 'SELECT * FROM ' . UPLOADIFY_TABLE . ' WHERE `session_code`=? AND `element`=? ORDER BY `uploadify_id`';
            $stmt = $DBC->query($query, array((string) $session_code, $element_name));
        }
        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                $ra[] = $ar['file_name'];
            }
        }
        if (empty($ra)) {
            return false;
        } else {
            return $ra;
        }
    }

    /**
     * Edit image
     * @param string $action action
     * @param string $table_name table name
     * @param string $key key
     * @param int $record_id record ID
     * @return boolean
     */
    function editImageMulti($action, $table_name, $key, $record_id, $name_template = '') {
        if (!isset($record_id) or $record_id == 0) {
            return false;
        }
        $path = SITEBILL_DOCUMENT_ROOT . '/img/data/';
        $uploadify_path = SITEBILL_DOCUMENT_ROOT . $this->uploadify_dir;
        $session_key = (string) $this->get_session_key();
        $ra = array();
        //update image
        $images = $this->load_uploadify_images($session_key);
        if (!$images) {
            //Попробуем получить фото из внешнего запроса
            $images = $this->getExternalUploadifyImageArray();
            if (!$images) {
                return false;
            }
        }

        if ($action == 'data') {
            $DBC = DBC::getInstance();

            $avial_count = (int) $this->getConfigValue('photo_per_data');
            if ($avial_count == 0) {
                $avial_count = 1000;
            } else {
                $loaded = 0;
                $query = 'SELECT COUNT(data_image_id) AS cnt FROM ' . DB_PREFIX . '_' . $table_name . '_image WHERE ' . $key . '=' . $record_id;
                $stmt = $DBC->query($query);
                if ($stmt) {
                    $ar = $DBC->fetch($stmt);
                    $loaded = (int) $ar['cnt'];
                }
                $avial_count = $avial_count - $loaded;
                if ($avial_count < 1) {
                    $this->delete_uploadify_images($session_key);
                    return false;
                }
            }

            if (count($images) > $avial_count) {
                $images = array_slice($images, 0, $avial_count);
            }
        }


        foreach ($images as $image_name) {
            $i++;
            $need_prv = 0;
            $preview_name = '';
            if (!empty($image_name)) {
                $arr = explode('.', $image_name);
                $ext = strtolower($arr[count($arr) - 1]);

                if (function_exists('exif_read_data')) {
                    $exif = exif_read_data($uploadify_path . $image_name, 0, true);
                    if (false === empty($exif['IFD0']['Orientation'])) {
                        switch ($exif['IFD0']['Orientation']) {
                            case 8:
                                $this->rotateImageInDestination($uploadify_path . $image_name, $uploadify_path . $image_name, 90);
                                break;
                            case 3:
                                $this->rotateImageInDestination($uploadify_path . $image_name, $uploadify_path . $image_name, 180);
                                break;
                            case 6:
                                $this->rotateImageInDestination($uploadify_path . $image_name, $uploadify_path . $image_name, -90);
                                break;
                        }
                    }
                }

                if ((1 == $this->getConfigValue('seo_photo_name_enable')) AND ( $name_template != '')) {
                    $name_template = substr($name_template, 0, 150);
                    if ($i == 0) {
                        $preview_name_no_ext = $name_template;
                        $prv_no_ext = $name_template . "_prev";
                    } else {
                        $preview_name_no_ext = $name_template . "_" . $i;
                        $prv_no_ext = $name_template . "_prev" . $i;
                    }

                    if (file_exists($path . $preview_name_no_ext . "." . $ext)) {
                        $rand = rand(0, 1000);
                        while (file_exists($path . $preview_name_no_ext . "_" . $rand . "." . $ext)) {
                            $rand = rand(0, 1000);
                        }
                        $preview_name = $preview_name_no_ext . "_" . $rand . "." . $ext;
                        $prv = $prv_no_ext . "_" . $rand . "." . $ext;
                    } else {
                        $preview_name = $preview_name_no_ext . "." . $ext;
                        $prv = $prv_no_ext . "." . $ext;
                    }
                } else {
                    $preview_name = "img" . uniqid() . '_' . time() . "_" . $i . "." . $ext;
                    $prv = "prv" . uniqid() . '_' . time() . "_" . $i . "." . $ext;
                    $preview_name_tmp = "_tmp" . uniqid() . '_' . time() . "_" . $i . "." . $ext;
                }

                if (in_array($ext, array('jpg', 'jpeg', 'gif', 'png'))) {

                    //print_r($this->config_array);	
                    //echo $action.'_image_big_width';

                    $big_width = $this->getConfigValue($action . '_image_big_width');
                    if ($big_width == '') {
                        $big_width = $this->getConfigValue('news_image_big_width');
                    }
                    $big_height = $this->getConfigValue($action . '_image_big_height');
                    if ($big_height == '') {
                        $big_height = $this->getConfigValue('news_image_big_height');
                    }

                    $preview_width = $this->getConfigValue($action . '_image_preview_width');
                    if ($preview_width == '') {
                        $preview_width = $this->getConfigValue('news_image_preview_width');
                    }
                    $preview_height = $this->getConfigValue($action . '_image_preview_height');
                    if ($preview_height == '') {
                        $preview_height = $this->getConfigValue('news_image_preview_height');
                    }

                    if (defined('STR_MEDIA') && STR_MEDIA == Sitebill::MEDIA_SAVE_FOLDER) {
                        if (defined('STR_MEDIA_FOLDERFDAYS') && STR_MEDIA_FOLDERFDAYS === 1) {
                            $foldeformat = 'Ymd';
                        } else {
                            $foldeformat = 'Ym';
                        }
                        $folder_name = date($foldeformat, time());
                        $locs = MEDIA_FOLDER . '/' . $folder_name;
                        if (!is_dir($locs)) {
                            mkdir($locs);
                        }
                        $preview_name = $folder_name . '/' . $preview_name;
                        $prv = $folder_name . '/' . $prv;
                    }
                    /* if(defined('STR_MEDIA') && STR_MEDIA=='new'){
                      $file_name=md5(uniqid().time().rand(1000,999).$i);
                      //$folder_name=substr($file_name, 0, 4);
                      $folder_name=date('Ym', time());

                      $locs=MEDIA_FOLDER.'/'.$folder_name;
                      if(!is_dir($locs)){
                      mkdir($locs);
                      }

                      $preview_name=$folder_name.'/'.$file_name.'.src.'.$ext;
                      $prv=$folder_name.'/'.$file_name.'.prv.'.$ext;


                      }elseif(defined('STR_MEDIA') && STR_MEDIA=='semi'){
                      $folder_name=date('Ym', time());
                      $locs=MEDIA_FOLDER.'/'.$folder_name;
                      if(!is_dir($locs)){
                      mkdir($locs);
                      }
                      $preview_name=$folder_name.'/'.$preview_name;
                      $prv=$folder_name.'/'.$prv;
                      }

                     */

                    $rn = $this->makePreview($uploadify_path . $image_name, $path . $preview_name, $big_width, $big_height, $ext, 1);
                    if (1 == $this->getConfigValue('apps.realty.preview_smart_resizing') && $action == 'data') {
                        $rp = $this->makePreview($uploadify_path . $image_name, $path . $prv, $preview_width, $preview_height, $ext, 'smart');
                    } else {
                        $rp = $this->makePreview($uploadify_path . $image_name, $path . $prv, $preview_width, $preview_height, $ext, 'width');
                    }
                    if ($rp && $rn) {
                        $this->doWatermark($path . $preview_name, $path . $prv);

                        /* На случай, если сервер выставляет на загруженные файлы права 0600 */
                        chmod($path . $preview_name, 0644);
                        chmod($path . $prv, 0644);
                        /**/

                        $ra[$i]['preview'] = $prv;
                        $ra[$i]['normal'] = $preview_name;
                    }
                }
            }
        }

        $this->add_image_records($ra, $table_name, $key, $record_id);
        $this->delete_uploadify_images($this->get_session_key());
        return $ra;
    }

    function doWatermark($normal_image, $preview_image) {
        if ($this->getConfigValue('is_watermark')) {
            if (!$this->watermark_inst) {
                require_once SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/watermark/watermark.php';
                $this->watermark_inst = new Watermark();
                $this->watermark_inst->setPosition($this->getConfigValue('apps.watermark.position'));
                $this->watermark_inst->setOffsets(array(
                    $this->getConfigValue('apps.watermark.offset_left'),
                    $this->getConfigValue('apps.watermark.offset_top'),
                    $this->getConfigValue('apps.watermark.offset_right'),
                    $this->getConfigValue('apps.watermark.offset_bottom')
                ));
            }

            $this->watermark_inst->printWatermark($normal_image);
            if ($this->getConfigValue('apps.watermark.preview_enable')) {
                $this->watermark_inst->printWatermark($preview_image, true);
            }
            return true;
        }
        return false;
    }

    /**
     * Эта функция устанавливает массив с картинками для эмитации загрузки картинок в UPLOADIFY
     * Используется в APPS.API для загрузки картинок из мобильного приложения
     * @param $_image_array - массив с картинками
     * @return void
     */
    function setExternalUploadifyImageArray($_image_array) {
        $this->external_uploadify_image_array = $_image_array;
    }

    function getExternalUploadifyImageArray() {
        return $this->external_uploadify_image_array;
    }

    function appendDocUploads($table, $field, $pk_field, $record_id) {
        $field_name = $field['name'];
        $parameters = $field['parameters'];
        $session_key = (string) $this->get_session_key();
        $action = $table;
        if (!isset($record_id) || $record_id == 0) {
            return false;
        }

        $DBC = DBC::getInstance();

        $path = SITEBILL_DOCUMENT_ROOT . '/img/mediadocs/';
        $uploadify_path = SITEBILL_DOCUMENT_ROOT . $this->uploadify_dir;

        $ra = array();
        $uploads = $this->load_uploadify_images($session_key, $field_name);
        if (!$uploads) {
            return false;
        }


        $query = 'SELECT `' . $field_name . '` FROM ' . DB_PREFIX . '_' . $table . ' WHERE `' . $pk_field . '`=? LIMIT 1';

        $stmt = $DBC->query($query, array($record_id));
        if (!$stmt) {
            return false;
        }
        $ar = $DBC->fetch($stmt);

        if ($ar[$field_name] === '') {
            $attached_yet = array();
        } else {
            $attached_yet = unserialize($ar[$field_name]);
        }
        //print_r($attached_yet);
        $i = 0;
        $max_filesize = (int) str_replace('M', '', ini_get('upload_max_filesize'));
        if (isset($parameters['max_file_size']) && (int) $parameters['max_file_size'] != 0) {
            $max_filesize = (int) $parameters['max_file_size'];
        }
        $av = explode(',', $parameters['accepted']);
        $allowed_exts = array('doc', 'xls', 'pdf', 'xlsx', 'txt', 'csv');
        if (!empty($av)) {
            foreach ($av as $k => $v) {
                $v = trim(ltrim($v, '.'));
                if ($v == '') {
                    unset($av[$k]);
                } else {
                    $av[$k] = $v;
                }
            }
        }
        if (!empty($av)) {
            $allowed_exts = $av;
        }
        //print_r($allowed_exts);
        foreach ($uploads as $image_name) {
            $i++;

            if (!empty($image_name)) {

                $arr = explode('.', $image_name);
                $ext = strtolower(end($arr));

                if (!in_array($ext, $allowed_exts)) {
                    continue;
                }
                $filesize = filesize($uploadify_path . $image_name) / (1024 * 1024);
                if ($filesize > $max_filesize) {
                    continue;
                }
                if ($this->getConfigValue('use_native_file_name_on_uploadify')) {
                    $file_name = $image_name;
                } else {
                    $file_name = "doc" . uniqid() . '_' . time() . '_' . $i . '.' . $ext;
                }
                $file_index = '';
                while (file_exists($path . $file_name)) {
                    $i++;
                    if ($this->getConfigValue('use_native_file_name_on_uploadify')) {
                        $file_name = $file_index . $file_name;
                    } else {
                        $file_name = "doc" . uniqid() . '_' . time() . '_' . $i . '.' . $ext;
                    }
                    $file_index++;
                }


                if (copy($uploadify_path . $image_name, $path . $file_name)) {
                    chmod($path . $file_name, 0644);
                    /**/
                    $ra[$i]['preview'] = '';
                    $ra[$i]['normal'] = $file_name;

                    $attached_yet[] = array('preview' => '', 'normal' => $file_name, 'type' => 'doc', 'mime' => $ext);
                }
            }
        }

        $query = 'UPDATE ' . DB_PREFIX . '_' . $table . ' SET `' . $field_name . '`=? WHERE `' . $pk_field . '`=?';
        if (count($attached_yet) > 0) {
            $stmt = $DBC->query($query, array(serialize($attached_yet), $record_id));
        } else {
            $stmt = $DBC->query($query, array('', $record_id));
        }
        //$this->add_image_records($ra, $table_name, $key, $record_id);
        $this->delete_uploadify_images($session_key, $field_name);
        return $ra;
    }

    function appendUploads($table, $field, $pk_field, $record_id, $name_template = '') {
        $field_name = $field['name'];
        $parameters = $field['parameters'];
        $session_key = (string) $this->get_session_key();


        $action = $table;
        if (!isset($record_id) || $record_id == 0) {
            return false;
        }

        $DBC = DBC::getInstance();

        $path = SITEBILL_DOCUMENT_ROOT . '/img/data/';
        $uploadify_path = SITEBILL_DOCUMENT_ROOT . $this->uploadify_dir;

        $ra = array();
        $uploads = $this->load_uploadify_images($session_key, $field_name);
        if (!$uploads) {
            $uploads = $this->getExternalUploadifyImageArray();
            if (!$uploads) {
                return false;
            }
        }

        if (isset($parameters['max_img_count']) && $parameters['max_img_count'] != '') {
            $max_img_count = intval($parameters['max_img_count']);
        } else {
            $max_img_count = -1;
        }

        $query = 'SELECT `' . $field_name . '` FROM ' . DB_PREFIX . '_' . $table . ' WHERE `' . $pk_field . '`=? LIMIT 1';

        $stmt = $DBC->query($query, array($record_id));
        if (!$stmt) {
            return false;
        }
        $ar = $DBC->fetch($stmt);

        if ($ar[$field_name] === '') {
            $attached_yet = array();
        } else {
            $attached_yet = unserialize($ar[$field_name]);
        }
        $i = 0;
        $max_filesize = (int) str_replace('M', '', ini_get('upload_max_filesize'));
        if (isset($parameters['max_file_size']) && (int) $parameters['max_file_size'] != 0) {
            $max_filesize = (int) $parameters['max_file_size'];
        }

        if ($max_img_count > -1) {
            $last_count = $max_img_count - count($attached_yet);
            if ($last_count > 0) {
                $uploads = array_slice($uploads, 0, $last_count);
            } else {
                $uploads = array();
            }
        }
        if (!empty($uploads)) {

            $folder_name = '';
            if (defined('STR_MEDIA') && STR_MEDIA == Sitebill::MEDIA_SAVE_FOLDER) {
                if (defined('STR_MEDIA_FOLDERFDAYS') && STR_MEDIA_FOLDERFDAYS === 1) {
                    $foldeformat = 'Ymd';
                } else {
                    $foldeformat = 'Ym';
                }
                $folder_name = date($foldeformat, time());
                $locs = MEDIA_FOLDER . '/' . $folder_name;
                if (!is_dir($locs)) {
                    mkdir($locs);
                }
                $preview_name = $folder_name . '/' . $preview_name;
                $prv = $folder_name . '/' . $prv;
            } elseif (defined('STR_MEDIA_DIVIDED') && STR_MEDIA_DIVIDED == 1) {                
                $fold1 = rand(0, 99);
                $fold2 = rand(0, 99);
                if ($fold1 < 10) {
                    $fold1 = '0' . $fold1;
                }
                if ($fold2 < 10) {
                    $fold2 = '0' . $fold2;
                }
                $folder_name = $fold1 . '/' . $fold2;
                $locs = MEDIA_FOLDER . '/' . $fold1;
                if (!is_dir($locs)) {
                    mkdir($locs);
                }
                $locs = MEDIA_FOLDER . '/' . $fold1 . '/' . $fold2;
                if (!is_dir($locs)) {
                    mkdir($locs);
                }
                /*
                 * Вариант вложенных папок для стандартных настроек
                 * папки от /000/000/ до /1f4/1f4/
                 * 500 вариантов / 500 вариантов
                 * в итоге не более 500 вариантов папок на одном уровне
                $fold1 = dechex(rand(0, 500));
                $fold2 = dechex(rand(0, 500));
                if(strlen($fold1) == 1){
                    $fold1 = '00' . $fold1;
                }elseif(strlen($fold1) == 2){
                    $fold1 = '0' . $fold1;
                }
                if(strlen($fold2) < 2){
                    $fold2 = '0' . $fold2;
                }elseif(strlen($fold2) == 2){
                    $fold1 = '0' . $fold2;
                }
                $folder_name = $fold1 . '/' . $fold2;
                $locs = MEDIA_FOLDER . '/' . $fold1;
                if (!is_dir($locs)) {
                    mkdir($locs);
                }
                $locs = MEDIA_FOLDER . '/' . $fold1 . '/' . $fold2;
                if (!is_dir($locs)) {
                    mkdir($locs);
                }
                */
                
            } else {
                $folder_name = '';
            }

            $uniq_file_name = uniqid() . '_' . time();

            foreach ($uploads as $image_name) {
                $i++;
                $need_prv = 0;
                $preview_name = '';
                $filesize = filesize($uploadify_path . $image_name) / (1024 * 1024);
                if ($filesize > $max_filesize) {
                    continue;
                }
                if (!empty($image_name)) {
                    $arr = explode('.', $image_name);
                    $ext = strtolower(end($arr));



                    if (function_exists('exif_read_data')) {
                        $exif = @exif_read_data($uploadify_path . $image_name, 0, true);
                        if (isset($exif['IFD0']) && isset($exif['IFD0']['Orientation']) && false === empty($exif['IFD0']['Orientation'])) {
                            switch ($exif['IFD0']['Orientation']) {
                                case 8:
                                    $this->rotateImageInDestination($uploadify_path . $image_name, $uploadify_path . $image_name, 90);
                                    break;
                                case 3:
                                    $this->rotateImageInDestination($uploadify_path . $image_name, $uploadify_path . $image_name, 180);
                                    break;
                                case 6:
                                    $this->rotateImageInDestination($uploadify_path . $image_name, $uploadify_path . $image_name, -90);
                                    break;
                            }
                        }
                    }
                    //$ext=strtolower($arr[count($arr)-1]);
                    if ((1 == $this->getConfigValue('seo_photo_name_enable')) AND ( $name_template != '')) {
                        $name_template = substr($name_template, 0, 150);
                        if ($i == 0) {
                            $preview_name_no_ext = $name_template;
                            $prv_no_ext = $name_template . "_prev";
                        } else {
                            $preview_name_no_ext = $name_template . "_" . $i;
                            $prv_no_ext = $name_template . "_prev" . $i;
                        }

                        if (file_exists($path . $preview_name_no_ext . "." . $ext)) {
                            $rand = rand(0, 1000);
                            while (file_exists($path . $preview_name_no_ext . "_" . $rand . "." . $ext)) {
                                $rand = rand(0, 1000);
                            }
                            $preview_name = $preview_name_no_ext . "_" . $rand . "." . $ext;
                            $prv = $prv_no_ext . "_" . $rand . "." . $ext;
                        } else {
                            $preview_name = $preview_name_no_ext . "." . $ext;
                            $prv = $prv_no_ext . "." . $ext;
                        }
                    } else {
                        $nm = $uniq_file_name . '_' . $i;
                        $preview_name = 'img' . $nm . "." . $ext;
                        $prv = "prv" . $nm . "." . $ext;
                        $preview_name_tmp = "_tmp" . uniqid() . '_' . time() . "_" . $i . "." . $ext;
                    }

                    if (in_array($ext, array('jpg', 'jpeg', 'gif', 'png', 'webp'))) {
                        $big_width = $this->getConfigValue($action . '_image_big_width');
                        if ($big_width == '') {
                            $big_width = $this->getConfigValue('data_image_big_width');
                        }
                        $big_height = $this->getConfigValue($action . '_image_big_height');
                        if ($big_height == '') {
                            $big_height = $this->getConfigValue('data_image_big_height');
                        }

                        $preview_width = $this->getConfigValue($action . '_image_preview_width');
                        if ($preview_width == '') {
                            $preview_width = $this->getConfigValue('data_image_preview_width');
                        }
                        $preview_height = $this->getConfigValue($action . '_image_preview_height');
                        if ($preview_height == '') {
                            $preview_height = $this->getConfigValue('data_image_preview_height');
                        }

                        if (isset($parameters['norm_width']) && (int) $parameters['norm_width'] != 0) {
                            $big_width = (int) $parameters['norm_width'];
                        }

                        if (isset($parameters['norm_height']) && (int) $parameters['norm_height'] != 0) {
                            $big_height = (int) $parameters['norm_height'];
                        }

                        if (isset($parameters['prev_width']) && (int) $parameters['prev_width'] != 0) {
                            $preview_width = (int) $parameters['prev_width'];
                        }

                        if (isset($parameters['prev_height']) && (int) $parameters['prev_height'] != 0) {
                            $preview_height = (int) $parameters['prev_height'];
                        }

                        /* if (defined('STR_MEDIA') && STR_MEDIA == Sitebill::MEDIA_SAVE_FOLDER) {
                          if (defined('STR_MEDIA_FOLDERFDAYS') && STR_MEDIA_FOLDERFDAYS === 1) {
                          $foldeformat = 'Ymd';
                          } else {
                          $foldeformat = 'Ym';
                          }
                          $folder_name = date($foldeformat, time());
                          $locs = MEDIA_FOLDER . '/' . $folder_name;
                          if (!is_dir($locs)) {
                          mkdir($locs);
                          }
                          $preview_name = $folder_name . '/' . $preview_name;
                          $prv = $folder_name . '/' . $prv;
                          } */

                        if ($folder_name != '') {
                            $preview_name = $folder_name . '/' . $preview_name;
                            $prv = $folder_name . '/' . $prv;
                        }



                        /*
                          if(defined('STR_MEDIA') && STR_MEDIA=='new'){
                          $file_name=md5(uniqid().time().rand(1000,999).$i);
                          $folder_name=date('Ym', time());

                          $locs=MEDIA_FOLDER.'/'.$folder_name;
                          if(!is_dir($locs)){
                          mkdir($locs);
                          }

                          $preview_name=$folder_name.'/'.$file_name.'.src.'.$ext;
                          $prv=$folder_name.'/'.$file_name.'.prv.'.$ext;
                          }elseif(defined('STR_MEDIA') && STR_MEDIA=='semi'){
                          $folder_name=date('Ym', time());
                          $locs=MEDIA_FOLDER.'/'.$folder_name;
                          if(!is_dir($locs)){
                          mkdir($locs);
                          }
                          $preview_name=$folder_name.'/'.$preview_name;
                          $prv=$folder_name.'/'.$prv;
                          } */
                        if (intval($parameters['normal_smart_resizing']) == 1) {
                            $rn = $this->makePreview($uploadify_path . $image_name, $path . $preview_name, $big_width, $big_height, $ext, 'smart');
                        } else {
                            $rn = $this->makePreview($uploadify_path . $image_name, $path . $preview_name, $big_width, $big_height, $ext, 1);
                        }

                        $preview_smart_resizing = false;
                        if (isset($parameters['preview_smart_resizing'])) {
                            if (intval($parameters['preview_smart_resizing']) == 1) {
                                $preview_smart_resizing = true;
                            } else {
                                $preview_smart_resizing = false;
                            }
                        } elseif (1 == $this->getConfigValue('apps.realty.preview_smart_resizing') && $action == 'data') {
                            $preview_smart_resizing = true;
                        }

                        if ($preview_smart_resizing) {
                            $rp = $this->makePreview($uploadify_path . $image_name, $path . $prv, $preview_width, $preview_height, $ext, 'smart');
                        } else {
                            $rp = $this->makePreview($uploadify_path . $image_name, $path . $prv, $preview_width, $preview_height, $ext, 'width');
                        }

                        /* if (1 == $this->getConfigValue('apps.realty.preview_smart_resizing') && $action == 'data') {
                          $rp = $this->makePreview($uploadify_path . $image_name, $path . $prv, $preview_width, $preview_height, $ext, 'smart');
                          } elseif (isset($parameters['preview_smart_resizing']) && (int) $parameters['preview_smart_resizing'] != 0) {
                          $rp = $this->makePreview($uploadify_path . $image_name, $path . $prv, $preview_width, $preview_height, $ext, 'smart');
                          } else {
                          $rp = $this->makePreview($uploadify_path . $image_name, $path . $prv, $preview_width, $preview_height, $ext, 'width');
                          } */




                        if ($rn && $rp) {
                            $this->doWatermark($path . $preview_name, $path . $prv);

                            /* На случай, если сервер выставляет на загруженные файлы права 0600 */
                            chmod($path . $preview_name, 0644);
                            chmod($path . $prv, 0644);
                            /**/
                            $ra[$i]['preview'] = $prv;
                            $ra[$i]['normal'] = $preview_name;
                        }
                    }
                    if ($rn && $rp) {
                        $attached_yet[] = array('preview' => $prv, 'normal' => $preview_name, 'type' => 'graphic', 'mime' => $ext);
                    }
                }
            }

            $query = 'UPDATE ' . DB_PREFIX . '_' . $table . ' SET `' . $field_name . '`=? WHERE `' . $pk_field . '`=?';
            if (count($attached_yet) > 0) {
                $stmt = $DBC->query($query, array(serialize($attached_yet), $record_id));
            } else {
                $stmt = $DBC->query($query, array('', $record_id));
            }
        }

        $this->delete_uploadify_images($session_key, $field_name);
        return $ra;
    }

    function rotateImageInDestination($source_image, $destination, $degree) {

        $arr = explode('.', $source_image);
        $ext = end($arr);

        if ($source_image == '') {
            return '';
        }

        if ($ext == 'jpg' || $ext == 'jpeg') {
            $source_image_res = @imagecreatefromjpeg($source_image);
        } elseif ($ext == 'png') {
            $source_image_res = @imagecreatefrompng($source_image);
        } elseif ($ext == 'gif') {
            $source_image_res = @imagecreatefromgif($source_image);
        } elseif ($ext == 'webp') {
            $source_image_res = @imagecreatefromwebp($source_image);
        }

        if (false === $source_image_res) {
            return;
        }

        $im = imagerotate($source_image_res, $degree, 0);

        if ($ext == 'jpg' || $ext == 'jpeg') {
            $im = imagerotate($source_image_res, $degree, 0);
            imagejpeg($im, $destination, (int) $this->getConfigValue('jpeg_quality'));
        } elseif ($ext == 'png') {
            $im = imagerotate($source_image_res, $degree, 0);
            imagepng($im, $destination, (int) $this->getConfigValue('png_quality'));
        } elseif ($ext == 'gif') {
            $im = imagerotate($source_image_res, $degree, 0);
            imagegif($im, $destination);
        } elseif ($ext == 'webp') {
            $im = imagerotate($source_image_res, $degree, 0);
            imagewebp($im, $destination);
        }

        return;
    }

    /**
     * Edit file
     * @param string $action action
     * @param string $table_name table name
     * @param string $key key
     * @param int $record_id record ID
     * @return boolean
     */
    function editFileMulti($action, $table_name, $key, $record_id) {
        $path = SITEBILL_DOCUMENT_ROOT . '/img/data/';
        $uploadify_path = SITEBILL_DOCUMENT_ROOT . $this->uploadify_dir;

        $ra = array();
        $images = $this->load_uploadify_images($this->get_session_key());
        if (!$images) {
            return;
        }

        foreach ($images as $image_name) {
            $i++;
            $need_prv = 0;
            $preview_name = '';
            if (!empty($image_name)) {
                $arr = explode('.', $image_name);
                $ext = strtolower(end($arr));
                $preview_name = "file" . uniqid() . '_' . time() . "_" . $i . "." . $ext;
                $prv = "ffile" . uniqid() . '_' . time() . "_" . $i . "." . $ext;
                $preview_name_tmp = "_tmp" . uniqid() . '_' . time() . "_" . $i . "." . $ext;


                list($width, $height) = $this->makeMove($uploadify_path . $image_name, $path . $preview_name);
                $ra[$i]['preview'] = $preview_name;
                $ra[$i]['normal'] = $preview_name;
            }
        }
        $this->add_image_records($ra, $table_name, $key, $record_id);
        $this->delete_uploadify_images($this->get_session_key());
        return $ra;
    }

    function clear_uploadify_table($session_code = '', $anyway = false) {
        if (1 == (int) $this->getConfigValue('dontclean_uploadify_table') && !$anyway) {
            return true;
        }

        $postloaded = array();
        if (isset($_POST['_formpostloaded']) && is_array($_POST['_formpostloaded']) && count($_POST['_formpostloaded']) > 0) {
            $_postloaded = $_POST['_formpostloaded'];
            foreach ($_postloaded as $list) {
                $postloaded = array_merge($postloaded, $list);
            }
        }

        $uploadify_path = SITEBILL_DOCUMENT_ROOT . $this->uploadify_dir;
        $DBC = DBC::getInstance();
        $ra = array();
        if ($session_code == '') {
            $query = "SELECT file_name FROM " . UPLOADIFY_TABLE;
            $stmt = $DBC->query($query);
        } else {
            $query = "SELECT file_name FROM " . UPLOADIFY_TABLE . ' WHERE session_code=?';
            $stmt = $DBC->query($query, array($session_code));
        }

        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                if (!in_array($ar['file_name'], $postloaded)) {
                    $ra[] = $ar['file_name'];
                }
            }
        }

        if (count($ra) > 0) {
            foreach ($ra as $image_name) {
                if (is_file($uploadify_path . $image_name)) {
                    unlink($uploadify_path . $image_name);
                }
            }
        }

        if ($session_code == '') {
            $query = "TRUNCATE TABLE " . UPLOADIFY_TABLE;
            $stmt = $DBC->query($query);
        } else {
            if (!empty($postloaded)) {
                $query = 'DELETE FROM ' . UPLOADIFY_TABLE . ' WHERE `session_code`=? AND `file_name` NOT IN (' . implode(',', array_fill(0, count($postloaded), '?')) . ')';
                array_unshift($postloaded, $session_code);
                $stmt = $DBC->query($query, $postloaded);
            } else {
                $query = 'DELETE FROM ' . UPLOADIFY_TABLE . ' WHERE `session_code`=?';
                $stmt = $DBC->query($query, array($session_code));
            }
        }

        return true;
    }

    function clear_captcha_session_table() {
        $limit_date = date('Y-m-d H:i:s', (time() - 24 * 3600));
        $DBC = DBC::getInstance();
        $q = 'DELETE FROM ' . DB_PREFIX . '_captcha_session WHERE start_date<?';
        $DBC->query($q, array($limit_date));
        return true;
    }

    function clear_session_table() {
        $limit_date = date('Y-m-d H:i:s', (time() - 24 * 3600));
        $DBC = DBC::getInstance();
        $q = 'DELETE FROM ' . DB_PREFIX . '_session WHERE start_date<?';
        $DBC->query($q, array($limit_date));
        return true;
    }

    /**
     * Delete uploadify images
     * @param string $session_code session code
     * @return array
     */
    function delete_uploadify_images($session_code, $element = '') {
        $uploadify_path = SITEBILL_DOCUMENT_ROOT . $this->uploadify_dir;
        $DBC = DBC::getInstance();

        $ra = array();
        if ($element != '') {
            $query = 'SELECT file_name FROM ' . UPLOADIFY_TABLE . ' WHERE `session_code`=? AND `element`=?';
            $stmt = $DBC->query($query, array((string) $session_code, $element));
        } else {
            $query = 'SELECT file_name FROM ' . UPLOADIFY_TABLE . ' WHERE `session_code`=?';
            $stmt = $DBC->query($query, array((string) $session_code));
        }



        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                $ra[] = $ar['file_name'];
            }
        }
        if (count($ra) > 0) {
            foreach ($ra as $image_name) {
                if (is_file($uploadify_path . $image_name)) {
                    unlink($uploadify_path . $image_name);
                }
            }
        }
        if ($element != '') {
            $query = 'DELETE FROM ' . UPLOADIFY_TABLE . ' WHERE `session_code`=? AND `element`=?';
            $stmt = $DBC->query($query, array((string) $session_code, $element));
        } else {
            $query = 'DELETE FROM ' . UPLOADIFY_TABLE . ' WHERE session_code=?';
            $stmt = $DBC->query($query, array((string) $session_code));
        }

        return true;
    }

    /**
     * Delete uploadify image
     * @param string $image_name image_name
     * @return array
     */
    function delete_uploadify_image($image_name) {
        $DBC = DBC::getInstance();
        $file_name = $image_name;
        $uploadify_path = SITEBILL_DOCUMENT_ROOT . $this->uploadify_dir;
        $query = 'DELETE FROM ' . UPLOADIFY_TABLE . ' WHERE file_name=?';
        $DBC->query($query, array($file_name));
        unlink($uploadify_path . $file_name);
        return true;
    }

    function get_ajax_auth_form() {
        if (SITEBILL_MAIN_URL != '') {
            $add_folder = SITEBILL_MAIN_URL . '/';
        }
        $rs .= '<form method="post" onsubmit="run_login(\'login\', \'cp1251\', \'' . $_SERVER['SERVER_NAME'] . $add_folder . '\'); return false;">';
        $rs .= '';
        $rs .= '<table border="0">';
        if ($this->getError() and $this->GetErrorMessage() != 'not login') {
            $rs .= '<tr>';
            $rs .= '<td colspan="2"><span class="error">' . $this->GetErrorMessage() . '</span></td>';
            $rs .= '</tr>';
        }
        $rs .= '<tr>';
        $rs .= '<td class="special" colspan="2"><div id="error_message"></div></td>';
        $rs .= '</tr>';

        $rs .= '<tr>';
        $rs .= '<td class="special">' . Multilanguage::_('L_LOGIN') . ' </td>';
        $rs .= '<td class="special"><input type="text" name="login" id="login"></td>';
        $rs .= '</tr>';

        $rs .= '<tr>';
        $rs .= '<td class="special">' . Multilanguage::_('L_PASSWORD') . ' </td>';
        $rs .= '<td class="special"><input type="password" name="password" id="password"></td>';
        $rs .= '</tr>';
        $rs .= '<tr>';
        $rs .= '<td class="special">';
        if ($this->getConfigValue('allow_register_admin')) {
            $rs .= '<a href="#" onclick="run_command(\'register\', \'cp1251\', \'' . $_SERVER['SERVER_NAME'] . $add_folder . '\'); return false;">' . Multilanguage::_('L_AUTH_REGISTRATION') . '</a>';
        }
        $rs .= '</td>';
        $rs .= '<td class="special"><input type="submit" value="' . Multilanguage::_('L_LOGIN_BUTTON') . '" onclick="run_login(\'login\', \'cp1251\', \'' . $_SERVER['SERVER_NAME'] . $add_folder . '\'); return false;"></td>';
        $rs .= '</tr>';
        $rs .= '</table>';
        $rs .= '';
        $rs .= '</form>';
        return $rs;
    }

    /**
     * Get simple auth form
     * @param string $action
     * @param boolean $register
     * @param boolean $remind
     * @return string
     */
    function get_simple_auth_form($action = '/login/', $register = true, $remind = true) {
        if (SITEBILL_MAIN_URL != '') {
            $add_folder = '/' . SITEBILL_MAIN_URL;
        }

        if ($this->getConfigValue('theme') == 'albostar') {
            $rs .= '<form method="post" action="' . SITEBILL_MAIN_URL . $action . '">';
            $rs .= '';

            if ($this->getError() and $this->GetErrorMessage() != 'not login') {
                $rs .= '<div>';
                $rs .= '<span class="error">' . $this->GetErrorMessage() . '</span>';
                $rs .= '</div>';
            }


            $rs .= '<label>' . Multilanguage::_('L_AUTH_LOGIN') . '</label>';
            $rs .= '<input type="text" name="login" id="login">';
            $rs .= '<br />';

            $rs .= '<label>' . Multilanguage::_('L_AUTH_PASSWORD') . '</label>';
            $rs .= '<input type="password" name="password" id="password">';
            $rs .= '<input type="submit" value="Вход">';
            if ($register) {
                $rs .= '<br />';
                $rs .= '<a href="' . SITEBILL_MAIN_URL . '/register/">' . Multilanguage::_('L_AUTH_REGISTRATION') . '</a>';
            }
            if ($remind) {
                $rs .= '<br />';
                $rs .= '<a href="' . SITEBILL_MAIN_URL . '/remind/">' . Multilanguage::_('L_AUTH_FORGOT_PASS') . '</a>';
            }

            $rs .= '<input type="hidden" name="do" value="login">';
            $rs .= '</form>';
        } else {

            if ($action == '/admin/' && 1 === intval($this->getConfigValue('use_captcha_admin_entry'))) {
                $c['captcha']['name'] = 'captcha';
                $c['captcha']['title'] = Multilanguage::_('CAPTCHA_TITLE', 'system');
                $c['captcha']['value'] = '';
                $c['captcha']['length'] = 40;
                $c['captcha']['type'] = 'captcha';
                $c['captcha']['required'] = 'on';
                $c['captcha']['unique'] = 'off';
                require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/form/form_generator.php');
                $form_generator = new Form_Generator();

                $el = $form_generator->compile_form_elements($c);
                $el = $el['hash']['captcha']['html'];
            } else {
                $el = '';
            }

            $rs .= '<form method="post" action="' . SITEBILL_MAIN_URL . $action . '">';
            if ($this->getError() and $this->GetErrorMessage() != 'not login') {
                $rs .= '<div class="alert alert-error" style="display:block;">';
                $rs .= '<a class="close" data-dismiss="alert" href="#">x</a>' . $this->GetErrorMessage() . '';
                $rs .= '</div>';
            }

            $rs .= '<input class="span12" placeholder="' . Multilanguage::_('L_AUTH_LOGIN') . '" type="text" name="login" id="login" />';
            $rs .= '<input class="span12" placeholder="' . Multilanguage::_('L_AUTH_PASSWORD') . '" type="password" name="password" id="password" />';
            $rs .= $el;
            $rs .= '<label class="checkbox">';
            $rs .= '<input type="checkbox" name="rememberme" value="1"> Запомнить меня';
            $rs .= '</label>';
            $rs .= '<button class="btn-info btn" type="submit">' . Multilanguage::_('L_AUTH_ENTER') . '</button>';
            $rs .= '<input type="hidden" name="do" value="login">';
            $rs .= '</form>';


            if ($register) {
                $rs .= '<a href="' . SITEBILL_MAIN_URL . '/register/">' . Multilanguage::_('L_AUTH_REGISTRATION') . '</a>';
            }
            if ($remind) {
                $rs .= '<br><a href="' . SITEBILL_MAIN_URL . '/remind/">' . Multilanguage::_('L_AUTH_FORGOT_PASS') . '</a>';
            }
        }
        return $rs;
    }

    /**
     * Add image data records
     * @param array $images images
     * @param string $table_name table name
     * @param string $key key
     * @param int $record_id record id
     * @return boolean
     */
    function add_image_records($images, $table_name, $key, $record_id) {

        $DBC = DBC::getInstance();
        foreach ($images as $item_id => $item_array) {
            $query = 'INSERT INTO ' . IMAGE_TABLE . ' (normal, preview) VALUES (?, ?)';
            $stmt = $DBC->query($query, array($item_array['normal'], $item_array['preview']));
            if ($stmt) {
                $image_id = $DBC->lastInsertId();
                $this->add_table_image_record($table_name, $key, $record_id, $image_id);
            }
        }
    }

    /**
     * Add table_image record
     * @param int $record_id record id
     * @param int $image_id image id
     * @return boolean
     */
    function add_table_image_record($table_name, $key, $record_id, $image_id) {
        $DBC = DBC::getInstance();
        $query = 'INSERT INTO ' . DB_PREFIX . '_' . $table_name . '_image (' . $key . ', image_id, sort_order) values (?, ?, ?)';
        $DBC->query($query, array($record_id, $image_id, $image_id));
        return true;
    }

    /**
     * Get Plupload plugin (http://www.plupload.com/)
     * Only html4 version available (not attached files for others)
     * @param string $session_code session code
     * @return string
     */
    function getPluploaderPlugin($session_code) {
        $this->clear_uploadify_table($session_code);
        global $folder;
        $rs .= '
    		
    		<style type="text/css">@import url(' . $folder . '/apps/system/js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>
			<script type="text/javascript" src="' . $folder . '/apps/system/js/plupload/plupload.full.js"></script>
			<script type="text/javascript" src="' . $folder . '/apps/system/js/plupload/jquery.plupload.queue/jquery.plupload.queue.js">
			<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
			<script type="text/javascript" src="' . $folder . '/apps/system/js/plupload/i18n/ru.js"></script>
			<script>        
		       $(function() {
		       		function log(msg){
		       			 $("#log").append(msg + "\n");
		       		
		       		};
		       		
		       		var del=[];
		       
					$("#html4_uploader").pluploadQueue({
						runtimes : \'html4\',
						multiple_queues: true,
						url : "' . $folder . '/apps/system/js/uploadify/uploadify.php?session=' . $session_code . '",
						init : {
							FileUploaded: function(up, file, info) {
								if (info.response.indexOf("wrong_ext") != -1){
									file.status = plupload.FAILED;
									up.trigger("UploadProgress", file);
								}else if(info.response.indexOf("max_file_size") != -1){
									file.status = plupload.FAILED;
									up.trigger("UploadProgress", file);
								}
							},
							
						}
					});
				});  
		    </script>  
			<div id="log"></div>
			<div id="html4_uploader">You browser doesnt support simple upload forms. Are you using Lynx?</div>';
        return $rs;
    }

    /**
     * Get uploadify plugin
     * @param string $session_code session code
     * @return string
     */
    function getUploadifyPlugin($session_code, $params = array()) {
        $this->clear_uploadify_table($session_code);
        $uploaded_images = $this->load_uploadify_images($session_code);
        global $folder;
        $rs = '';
        $rs .= '
<link href="' . $folder . '/apps/system/js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<style>
		#filecollector { overflow: hidden; }
		#filecollector div { width: 100px; display: block; float: left; padding: 5px; margin: 3px; }
		#filecollector div img { width: 100px; border: 1px solid #CFCFCF; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15); border-radius: 5px; margin-bottom: 5px; }
</style>
		
<script type="text/javascript" src="' . $folder . '/apps/system/js/uploadify/jquery.uploadify.js"></script>
<script type="text/javascript">
var uploadedfiles = 0;
var maxQueueSize = 100;
var queueSize = 0;
$(document).ready(function() {
	var max_item_count=' . ((int) $this->getConfigValue('photo_per_data') > 0 ? (int) $this->getConfigValue('photo_per_data') : 1000) . ';
	
	
	
  $(\'#file_upload\').uploadify({
    \'swf\'  : \'' . $folder . '/apps/system/js/uploadify/uploadify.swf\',
    \'uploader\'    : \'' . $folder . '/apps/system/js/uploadify/uploadify.php?session=' . $session_code . '\',
    \'cancelImg\' : \'' . $folder . '/apps/system/js/uploadify/uploadify-cancel.png\',
    \'folder\'    : \'' . $folder . '/cache/upl\',
    \'auto\'      : true,
	\'fileTypeExts\': \'*.jpg;*.jpeg;*.png;*.gif\',
	\'multi\': true,	
	\'queueSizeLimit\': 100,
		\'buttonText\': \'' . ((isset($params['button_name']) && $params['button_name'] != '') ? $params['button_name'] : Multilanguage::_('L_PHOTO')) . '\',	
	\'buttonImg\': \'' . $folder . '/img/button_img_upl.png\',	
    \'onUploadSuccess\': function(fileObj, response, data) {
    					queueSize++;
    					if ( response == \'max_file_size\' ) {
    						alert(\'' . Multilanguage::_('L_MESSAGE_MAX_UPL_SIZE') . ' ' . ini_get('upload_max_filesize') . ' \');
    						return false;
    					}
    					if ( response == \'wrong_ext\' ) {
    						alert(\'' . Multilanguage::_('L_MESSAGE_AVIALABLE_EXTS') . ' *.jpg,*.jpeg,*.png,*.gif\');
    						return false;
    					}
    					if ( response == \'bad_file\' ) {
    						alert(\'bad_file\');
    						return false;
    					}
    					if ( queueSize > maxQueueSize ) {
    						alert(\'' . Multilanguage::_('L_MESSAGE_MAX_FILES_COUNT') . '\');
    						return false;
    					}
    					var imgs_count=$("div.preview_admin").length+$("#filecollector img").length;
    					imgs_count++;
    					if(imgs_count==max_item_count){
    						$(\'#file_uploadUploader\').hide();
						}
    								
    					addFileNotify(queueSize);
    					addFileInCollector(response);
    					
    				}
                    
    });
    
});
function addFileNotify ( queueSize ) {
	$(\'#filenotify\').html( \'Вы успешно загрузили: \' + queueSize + \' файл(ов)\' );
}
function addFileInCollector ( filePath ) {
	var temp=new Array();
	temp=filePath.split(\'/\');
    //								temp=filePath.split(\'||\');
	var f=temp[temp.length-1];
	var cont=$(\'#filecollector\').html();
	cont=cont+\'<div><img src="\'+filePath+\'" /><a class="kill_upl btn btn-mini btn-danger" href="javascript:void(0)" alt="\'+f+\'">X</a></div>\';
    //								cont=cont+\'<div><img src="\'+temp[0]+\'" /><a class="kill_upl btn btn-mini btn-danger" href="javascript:void(0)" alt="\'+f+\'">X</a></div>\';
	$(\'#filecollector\').html(cont);
	
}

$(document).ready(function() {
	$(document).on(\'click\', \'a.kill_upl\',function(){
	
		var imgs_count=$("div.preview_admin").length+$("#filecollector img").length;
		var max_item_count=' . ((int) $this->getConfigValue('photo_per_data') > 0 ? (int) $this->getConfigValue('photo_per_data') : 1000) . ';
		var url=\'/js/ajax.php?action=delete_uploadify_image&img_name=\'+$(this).attr(\'alt\');
		$.getJSON(url,{},function(data){});
		var parent=$(this).parent(\'div\');
		parent.html(\'\');
		parent.remove();
		imgs_count--;
		if(imgs_count<max_item_count){
    		$(\'#file_uploadUploader\').show();
		}
	});
	
		
});

</script>
<input id="file_upload" name="file_upload" type="file" />
<div id="filenotify"></div>
<div id="filecollector">';
        if (false !== $uploaded_images) {
            foreach ($uploaded_images as $uplim) {
                $p = array();
                $p = explode('.', $uplim);
                if (in_array(strtolower(end($p)), array('jpg', 'jpeg', 'png', 'gif'))) {
                    $rs .= '<div><img src="' . SITEBILL_MAIN_URL . '/cache/upl/' . $uplim . '"><a class="kill_upl btn btn-mini btn-danger" href="javascript:void(0)" alt="' . $uplim . '">X</a></div>';
                }
            }
        }

        $rs .= '</div>';

        return $rs;
    }

    function getDropzonePlugin($session_code, $params = array()) {
        $element = $params['element']['name'];
        $type = $params['element']['type'];
        $rs = '';

        $this->clear_uploadify_table($session_code);

        $uploaded_images = $this->load_uploadify_images($session_code, $element);
        $id = 'dz_' . md5(time() . rand(100, 999));
        $Dropzone_name = 'Dropzone_' . md5(time() . rand(100, 999));

        if ((int) $params['min_img_count'] != 0) {
            $src = 'var formsubmit=$("#' . $id . '").parents("form").eq(0).find("[name=submit]");
					var vm=formsubmit.data("valid_me");
					if(vm === undefined){
						vm=[];
					}
					vm.push({id:"' . $id . '", count:' . (int) $params['min_img_count'] . '});
					formsubmit.data("valid_me", vm);';
        } else {
            $src = '';
        }


        $rs .= '<script>
    			
    			$(document).ready(function(){
    			
    			//var prevbuttonstatus_' . $Dropzone_name . ';
    				var ' . $Dropzone_name . ' = new Dropzone("div#' . $id . '", 
    				{ 
    					maxFilesize: ' . $params['max_file_size'] . ',
						url: "' . SITEBILL_MAIN_URL . '/apps/system/js/uploadify/uploadify.php?uploader_type=dropzone&element=' . $element . '&model=' . $params['element']['table_name'] . '&primary_key_value=' . $params['element']['primary_key_value'] . '&primary_key=' . $params['element']['primary_key'] . '",
	    				' . ($params['element']['parameters']['accepted'] != '' ? 'acceptedFiles: \'' . $params['element']['parameters']['accepted'] . '\',' : '') . '
						addRemoveLinks: true
					});
					$("div#' . $id . ' .dz-remove").click(function(){
							var _this=$(this);
							var url="' . SITEBILL_MAIN_URL . '/js/ajax.php?action=delete_uploadify_image&img_name="+$(this).attr("alt");
								$("#' . $id . ' .postloaded[value=\'"+$(this).attr("alt")+"\']").remove();
								$.getJSON(url,{},function(data){_this.parents(".dz-preview").eq(0).remove()});
    						});
					' . $src . ' 
					' . $Dropzone_name . '.on("complete", function(){
    						if(this.getQueuedFiles().length==0 && this.getUploadingFiles().length==0){
    							var form=$(this.element).parents("form");
								form.find("[name=submit]").show();	
    							//form.find("[name=submit]").prop("disabled", false);	
								//form.find("[name=submit]").prop("disabled", prevbuttonstatus' . $Dropzone_name . ');
							}
    
    				}).on("success", function(file, responce) {
							if(responce.status=="error"){
								$(file.previewElement).remove();
							if(typeof ' . $Dropzone_name . '_quenue !=\'undefined\' ){
								' . $Dropzone_name . '_quenue--;
										}
										var form=$(this.element).parents("form");
										
										//form.find("[name=submit]").prop("disabled", false);	
										//form.find("[name=submit]").prop("disabled", prevbuttonstatus' . $Dropzone_name . ');
												//console.log(prevbuttonstatus' . $Dropzone_name . ');
							}else{
														
								var form=$(this.element).parents("form");
														
								var rem=$(file.previewElement).find(".dz-remove");
								var temp=new Array();
								temp=responce.msg.split(\'/\');
								var file_name=temp[temp.length-1];
														$("#' . $id . '").append($("<input class=\'postloaded\' name=\'_formpostloaded[' . $element . '][]\' type=\'hidden\' value=\'"+file_name+"\'>"));
								rem.attr("alt", file_name);
								rem.on("click", function(){
    								var url="' . SITEBILL_MAIN_URL . '/js/ajax.php?action=delete_uploadify_image&img_name="+$(this).attr("alt");
									$.getJSON(url,{},function(data){});
    							});
							}
    						
    				}).on("addedfile", function(file){
    					var form=$(this.element).parents("form");
    					//prevbuttonstatus' . $Dropzone_name . '=form.find("[name=submit]").prop("disabled");
    					form.find("[name=submit]").hide();	
    					//form.find("[name=submit]").prop("disabled", true);		
    				});
				});
				</script>';
        $rs .= '<div data-ii="" class="dropzone_outer' . ($type == 'docuploads' ? ' docuploads' : '') . '"><div id="' . $id . '" class="dropzone_inner"><div class="dz-default dz-message"><span><span class="bigger-50 bolder">' . ($type == 'docuploads' ? Multilanguage::_('L_DOCUPLOADS_FILE') : Multilanguage::_('L_UPLOADS_FILE')) . '</span> <br>	<i class="upload-icon icon-cloud-upload blue icon-3x"></i></span></div>';
        if (false !== $uploaded_images) {
            foreach ($uploaded_images as $uplim) {

                $p = array();
                $p = explode('.', $uplim);
                if (($type == 'uploads' && in_array(strtolower(end($p)), array('jpg', 'jpeg', 'png', 'gif'))) || $type == 'docuploads') {
                    $rs .= '<input class="postloaded" name="_formpostloaded[' . $element . '][]" type="hidden" value="' . $uplim . '">';
                }
            }
        }

        if (false !== $uploaded_images) {
            foreach ($uploaded_images as $uplim) {

                $p = array();
                $p = explode('.', $uplim);

                if (($type == 'uploads' && in_array(strtolower(end($p)), array('jpg', 'jpeg', 'png', 'gif'))) || $type == 'docuploads') {
                    $rs .= '<div class="dz-preview dz-processing dz-image-preview dz-success">';
                    $rs .= '<div class="dz-details">';
                    $rs .= '<div class="dz-filename">';
                    $rs .= '<span data-dz-name="">' . $uplim . '</span></div>';
                    $rs .= '<div class="dz-size" data-dz-size="">';
                    $rs .= '<strong>0.1</strong> MiB</div>';
                    if ($type == 'uploads') {
                        $rs .= '<img data-dz-thumbnail="" alt="' . $uplim . '" src="' . SITEBILL_MAIN_URL . '/cache/upl/' . $uplim . '">';
                    }

                    $rs .= '</div>  <div class="dz-progress">';
                    $rs .= '<span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;">';
                    $rs .= '</span>';
                    $rs .= '</div>';
                    $rs .= '<div class="dz-success-mark"><span>✔</span></div>  <div class="dz-error-mark"><span>✘</span></div>  <div class="dz-error-message">';
                    $rs .= '<span data-dz-errormessage="">';
                    $rs .= '</span>';
                    $rs .= '</div>';
                    $rs .= '<a class="dz-remove" href="javascript:undefined;" data-dz-remove="" alt="' . $uplim . '">Удалить</a>';
                    $rs .= '</div>';
                }
            }
        }
        $rs .= '</div>';
        $rs .= '</div>';

        return $rs;
    }

    /**
     * Get uploadify plugin
     * @param string $session_code session code
     * @return string
     */
    function getUploadifyFilePlugin($session_code, $params = array()) {
        $this->clear_uploadify_table($session_code);
        $id = md5(time() . rand(1000, 9999));
        global $folder;

        $rs = '';
        $rs .= '
<link href="' . $folder . '/apps/system/js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="' . $folder . '/apps/system/js/uploadify/jquery.uploadify.js"></script>
<script type="text/javascript">
var uploadedfiles = 0;
var maxQueueSize = 100;
var queueSize = 0;
$(document).ready(function() {
  $(\'#' . $id . '\').uploadify({
    \'swf\'  : \'' . $folder . '/apps/system/js/uploadify/uploadify.swf\',
    \'uploader\'    : \'' . $folder . '/apps/system/js/uploadify/uploadify.php?file=1&session=' . $session_code . '\',
    \'cancelImg\' : \'' . $folder . '/apps/system/js/uploadify/uploadify-cancel.png\',
    \'folder\'    : \'' . $folder . '/cache/upl\',
    \'auto\'      : true,
	\'fileTypeExts\': \'*.doc;*.pdf;*.zip\',
	\'multi\': true,	
	\'queueSizeLimit\': 100,
	\'buttonText\': \'' . ((isset($params['button_name']) && $params['button_name'] != '') ? $params['button_name'] : Multilanguage::_('L_FILE')) . '\',	
	\'buttonImg\': \'' . $folder . '/img/button_img_upl.png\',	
    \'onUploadSuccess\': function(fileObj, response, data) {
    					queueSize++;
    					if ( response == \'max_file_size\' ) {
    						alert(\'' . Multilanguage::_('L_MESSAGE_MAX_UPL_SIZE') . ' ' . ini_get('upload_max_filesize') . ' \');
    						return false;
    					}
    					if ( response == \'wrong_ext\' ) {
    						alert(\'' . Multilanguage::_('L_MESSAGE_AVIALABLE_EXTS') . ' png, jpg, tif, jpeg, doc,docx, xls, xlsx, pdf, txt, zip, rar\');
    						return false;
    					}
    					if ( queueSize > maxQueueSize ) {
    						alert(\'' . Multilanguage::_('L_MESSAGE_MAX_FILES_COUNT') . '\');
    						return false;
    					}
    					addFileNotify(queueSize);
    				}
                    
    });
});
function addFileNotify ( queueSize ) {
	$(\'#filenotify\').html( \'Вы успешно загрузили: \' + queueSize + \' файл(ов)\' );
}
</script>
<input id="' . $id . '" name="file_upload" type="file" />
<div id="filenotify"></div>
        ';
        return $rs;
    }

    /**
     * Is demo
     * @param void
     * @return boolean
     */
    function isDemo() {
        global $__user, $__db;
        if (preg_match('/rumantic_estate/', $__db)) {
            return true;
        }
        return false;
    }

    /**
     * Demo function disabled
     * @param void
     * @return string
     */
    function demo_function_disabled() {
        return Multilanguage::_('L_MESSAGE_THIS_IS_TRIAL_COMMON');
    }

    /**
     * Load config
     * @param
     * @return
     */
    function loadConfig() {
        if (!self::$config_loaded) {
            $SConfig = SConfig::getInstance();
            self::$config_array = $SConfig->getConfig();
            self::$config_loaded = true;
        }
    }

    /**
     * Delete image
     * @param string $table_name table name
     * @param int $image_id image id
     * @return boolean
     */
    function deleteImage($table_name, $image_id) {
        $DBC = DBC::getInstance();
        $query = 'DELETE FROM ' . DB_PREFIX . '_' . $table_name . '_image WHERE image_id=?';
        $DBC->query($query, array($image_id));

        $this->deleteImageFiles($image_id);

        $query = 'DELETE FROM ' . IMAGE_TABLE . ' WHERE image_id=?';
        $DBC->query($query, array($image_id));
        return true;
    }

    function makeImageMain($action, $image_id, $key, $key_value) {
        $DBC = DBC::getInstance();
        $query = 'SELECT image_id FROM ' . DB_PREFIX . '_' . $action . '_image WHERE `' . $key . '`=? ORDER BY sort_order';
        $stmt = $DBC->query($query, array($key_value));
        $imgs = array();
        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                $imgs[] = $ar['image_id'];
            }
        }

        if (!empty($imgs)) {
            $imgids = array_flip($imgs);
            if (isset($imgids[$image_id])) {
                unset($imgs[$imgids[$image_id]]);
                array_unshift($imgs, $image_id);
            }
            $query = 'UPDATE ' . DB_PREFIX . '_' . $action . '_image SET sort_order=? WHERE image_id=?';
            foreach ($imgs as $k => $v) {
                $DBC->query($query, array($k + 1, $v));
            }
        }
    }

    function rotateImage2($thisimage, $isWatermark, $degree, $parameters) {

        if ($thisimage['normal'] == '') {
            return '';
        }

        $arr = explode('.', $thisimage['normal']);
        $ext = end($arr);

        if ($isWatermark && file_exists(SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal'])) {
            $source_image = SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal'];
        } elseif (file_exists(SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'])) {
            $source_image = SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'];
        } else {
            $source_image = '';
        }

        if ($source_image == '') {
            return '';
        }

        $source_preview = SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['preview'];

        $big_sizes = getimagesize($source_image);
        $prev_sizes = getimagesize($source_preview);

        if ($ext == 'jpg' || $ext == 'jpeg') {
            $source_image_res = imagecreatefromjpeg($source_image);
        } elseif ($ext == 'png') {
            $source_image_res = imagecreatefrompng($source_image);
        } elseif ($ext == 'gif') {
            $source_image_res = imagecreatefromgif($source_image);
        }



        $preview_width = $parameters['prev_width'];
        $preview_height = $parameters['prev_height'];

        if (1 == $parameters['preview_smart_resizing']) {
            $preview_mode = 'smart';
        } else {
            $preview_mode = 'width';
        }




        if ($isWatermark) {
            if ($ext == 'jpg' || $ext == 'jpeg') {
                $im = imagerotate($source_image_res, $degree, 0);
                @imagejpeg($im, SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal'], (int) $this->getConfigValue('jpeg_quality'));
                imagejpeg($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], (int) $this->getConfigValue('jpeg_quality'));
            } elseif ($ext == 'png') {
                $im = imagerotate($source_image_res, $degree, 0);
                @imagepng($im, SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal'], (int) $this->getConfigValue('png_quality'));
                imagepng($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], (int) $this->getConfigValue('png_quality'));
            } elseif ($ext == 'gif') {
                $im = imagerotate($source_image_res, $degree, 0);
                @imagegif($im, SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal']);
                imagegif($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal']);
            } elseif ($ext == 'webp') {
                $im = imagerotate($source_image_res, $degree, 0);
                @imagewebp($im, SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal']);
                imagewebp($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal']);
            }

            $rp = $this->makePreview($source_image, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['preview'], $preview_width, $preview_height, $ext, $preview_mode);
        } else {
            if ($ext == 'jpg' || $ext == 'jpeg') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagejpeg($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], (int) $this->getConfigValue('jpeg_quality'));
            } elseif ($ext == 'png') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagepng($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], (int) $this->getConfigValue('png_quality'));
            } elseif ($ext == 'gif') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagegif($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal']);
            } elseif ($ext == 'webp') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagewebp($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal']);
            }
            $rp = $this->makePreview($source_image, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['preview'], $preview_width, $preview_height, $ext, $preview_mode);
        }

        return true;
    }

    function rotateImage($action, $image_id, $key, $key_value, $rot_dir) {
        if ($rot_dir == 'ccw') {
            $degree = 90;
        } else {
            $degree = -90;
        }

        $DBC = DBC::getInstance();
        $query = 'SELECT normal, preview FROM ' . DB_PREFIX . '_image WHERE `image_id`=? LIMIT 1';
        $normal = '';
        $stmt = $DBC->query($query, array($image_id));
        $imgs = array();
        if ($stmt) {
            $ar = $DBC->fetch($stmt);
            $thisimage = $ar;
        }

        if ($thisimage['normal'] == '') {
            return '';
        }

        $arr = explode('.', $thisimage['normal']);
        $ext = end($arr);

        $hasWatermark = false;
        if (file_exists(SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal'])) {
            $source_image = SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal'];
            $hasWatermark = true;
        } elseif (file_exists(SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'])) {
            $source_image = SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'];
        } else {
            $source_image = '';
        }

        if ($source_image == '') {
            return '';
        }

        $source_preview = SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['preview'];

        $big_sizes = getimagesize($source_image);
        $prev_sizes = getimagesize($source_preview);

        if ($ext == 'jpg' || $ext == 'jpeg') {
            $source_image_res = imagecreatefromjpeg($source_image);
        } elseif ($ext == 'png') {
            $source_image_res = imagecreatefrompng($source_image);
        } elseif ($ext == 'gif') {
            $source_image_res = imagecreatefromgif($source_image);
        } elseif ($ext == 'webp') {
            $source_image_res = imagecreatefromwebp($source_image);
        }

        $preview_width = $this->getConfigValue($action . '_image_preview_width');
        if ($preview_width == '') {
            $preview_width = $this->getConfigValue('news_image_preview_width');
        }
        $preview_height = $this->getConfigValue($action . '_image_preview_height');
        if ($preview_height == '') {
            $preview_height = $this->getConfigValue('news_image_preview_height');
        }
        if (1 == $this->getConfigValue('apps.realty.preview_smart_resizing') && $action == 'data') {
            $preview_mode = 'smart';
        } else {
            $preview_mode = 'width';
        }

        if ($hasWatermark) {
            if ($ext == 'jpg' || $ext == 'jpeg') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagejpeg($im, SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal'], (int) $this->getConfigValue('jpeg_quality'));
                imagejpeg($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], 100);
            } elseif ($ext == 'png') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagepng($im, SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal'], (int) $this->getConfigValue('png_quality'));
                imagepng($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], (int) $this->getConfigValue('png_quality'));
            } elseif ($ext == 'gif') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagegif($im, SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal']);
                imagegif($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal']);
            } elseif ($ext == 'webp') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagewebp($im, SITEBILL_DOCUMENT_ROOT . '/img/data/nowatermark/' . $thisimage['normal']);
                imagewebp($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal']);
            }

            $rp = $this->makePreview(SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['preview'], $preview_width, $preview_height, $ext, 'smart');
        } else {
            if ($ext == 'jpg' || $ext == 'jpeg') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagejpeg($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], (int) $this->getConfigValue('jpeg_quality'));
            } elseif ($ext == 'png') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagepng($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], (int) $this->getConfigValue('png_quality'));
            } elseif ($ext == 'gif') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagegif($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal']);
            } elseif ($ext == 'webp') {
                $im = imagerotate($source_image_res, $degree, 0);
                imagewebp($im, SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal']);
            }
            $rp = $this->makePreview(SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['normal'], SITEBILL_DOCUMENT_ROOT . '/img/data/' . $thisimage['preview'], $preview_width, $preview_height, $ext, 'smart');
        }

        return;
    }

    /**
     * Reorder image
     * @param $action
     * @param $image_id
     * @param $key 
     * @param $key_value
     * @param $direction
     * @return mixed
     */
    /*
      function reorderImage($action, $image_id, $key, $key_value, $direction) {
      //echo $action.' '.$image_id.' '.$key.' '.$key_value.' '.$direction;
      $DBC=DBC::getInstance();
      //get current image info
      $query = 'SELECT '.$action.'_image_id, sort_order FROM '.DB_PREFIX.'_'.$action.'_image WHERE image_id=?';
      //echo $image_id;
      $stmt=$DBC->query($query, array($image_id));
      $rr=$DBC->fetch($stmt);

      $record_image_id=$rr[$action.'_image_id'];

      $sort_order = $rr['sort_order'];

      if ( $direction == 'down' ) {
      //get next image id
      $query = 'SELECT '.$action.'_image_id, sort_order FROM '.DB_PREFIX.'_'.$action.'_image WHERE sort_order > ? AND `'.$key.'` = ? ORDER BY sort_order ASC LIMIT 1';
      //echo $query;
      $stmt=$DBC->query($query, array($sort_order, $key_value));
      if($stmt){
      $ar=$DBC->fetch($stmt);
      $next_record_image_id = $ar[$action.'_image_id'];
      $next_sort_order = $ar['sort_order'];
      //echo $next_record_image_id.' '.$next_sort_order;

      $query = 'UPDATE '.DB_PREFIX.'_'.$action.'_image SET sort_order=? WHERE '.$action.'_image_id=?';
      $stmt=$DBC->query($query, array($next_sort_order, $record_image_id));
      $stmt=$DBC->query($query, array($sort_order, $next_record_image_id));
      }


      }

      if ( $direction == 'up' ) {
      //print_r($rr);
      //get next image id
      $query = 'SELECT '.$action.'_image_id, sort_order FROM '.DB_PREFIX.'_'.$action.'_image WHERE sort_order < ? AND `'.$key.'` = ? ORDER BY sort_order ASC LIMIT 1';
      //echo $query;
      $stmt=$DBC->query($query, array($sort_order, $key_value));
      if($stmt){
      $ar=$DBC->fetch($stmt);
      print_r($rr);
      print_r($ar);
      $next_record_image_id = $ar[$action.'_image_id'];
      $next_sort_order = $ar['sort_order'];

      $query = 'UPDATE '.DB_PREFIX.'_'.$action.'_image SET sort_order=? WHERE '.$action.'_image_id=?';
      $stmt=$DBC->query($query, array($next_sort_order, $record_image_id));

      //$query = 'UPDATE '.DB_PREFIX.'_'.$action.'_image SET sort_order=? WHERE '.$action.'_image_id=?';
      $stmt=$DBC->query($query, array($sort_order, $next_record_image_id));
      }

      }

      //get next image

      }
     */

    function reorderImage($action, $image_id, $key, $key_value, $direction) {
        $DBC = DBC::getInstance();
        $query = 'SELECT ' . $action . '_image_id, sort_order FROM ' . DB_PREFIX . '_' . $action . '_image WHERE image_id=?';
        $stmt = $DBC->query($query, array($image_id));
        $rr = array();
        if (!$stmt) {
            return;
        }
        $rr = $DBC->fetch($stmt);
        $record_image_id = $rr[$action . '_image_id'];
        $sort_order = $rr['sort_order'];

        if ($direction == 'down') {
            $query = 'SELECT ' . $action . '_image_id, sort_order FROM ' . DB_PREFIX . '_' . $action . '_image WHERE sort_order > ? AND `' . $key . '` = ? ORDER BY sort_order ASC';
            $stmt = $DBC->query($query, array($sort_order, $key_value));
            if (!$stmt) {
                return;
            }
            $rr = $DBC->fetch($stmt);
            $next_record_image_id = (int) $rr[$action . '_image_id'];
            if ($next_record_image_id == 0) {
                return;
            }
            $next_sort_order = $rr['sort_order'];

            $query = 'UPDATE ' . DB_PREFIX . '_' . $action . '_image SET sort_order=? WHERE ' . $action . '_image_id=?';
            $stmt = $DBC->query($query, array($next_sort_order, $record_image_id));

            $query = 'UPDATE ' . DB_PREFIX . '_' . $action . '_image SET sort_order=? WHERE ' . $action . '_image_id=?';
            $stmt = $DBC->query($query, array($sort_order, $next_record_image_id));
        }

        if ($direction == 'up') {
            $query = 'SELECT ' . $action . '_image_id, sort_order FROM ' . DB_PREFIX . '_' . $action . '_image WHERE sort_order < ? AND `' . $key . '` = ? ORDER BY sort_order DESC';
            $stmt = $DBC->query($query, array($sort_order, $key_value));
            if (!$stmt) {
                return;
            }
            $rr = $DBC->fetch($stmt);
            $next_record_image_id = (int) $rr[$action . '_image_id'];
            if ($next_record_image_id == 0) {
                return;
            }
            $next_sort_order = $rr['sort_order'];
            $query = 'UPDATE ' . DB_PREFIX . '_' . $action . '_image SET sort_order=? WHERE ' . $action . '_image_id=?';
            $stmt = $DBC->query($query, array($next_sort_order, $record_image_id));

            $query = 'UPDATE ' . DB_PREFIX . '_' . $action . '_image SET sort_order=? WHERE ' . $action . '_image_id=?';
            $stmt = $DBC->query($query, array($sort_order, $next_record_image_id));
        }
    }

    function reorderTopics($orderArray) {
        if (count($orderArray) > 0) {
            $DBC = DBC::getInstance();
            $query = 'UPDATE ' . DB_PREFIX . '_topic SET `order`=? WHERE id=?';
            foreach ($orderArray as $k => $v) {
                $DBC->query($query, array((int) $v, (int) $k));
            }
        }
    }

    /**
     * Delete image files
     * @param $image_id image id
     * @return boolean
     */
    function deleteImageFiles($image_id) {
        $path = SITEBILL_DOCUMENT_ROOT . $this->storage_dir;
        $DBC = DBC::getInstance();
        $query = 'SELECT * FROM ' . IMAGE_TABLE . ' WHERE image_id=?';
        $stmt = $DBC->query($query, array((int) $image_id));
        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                if (defined('STR_MEDIA') && STR_MEDIA == Sitebill::MEDIA_SAVE_FOLDER) {
                    $preview = $ar['preview'];
                    $normal = $ar['normal'];
                    @unlink(MEDIA_FOLDER . '/' . $preview);
                    @unlink(MEDIA_FOLDER . '/' . $normal);
                    @unlink(MEDIA_FOLDER . '/nowatermark/' . $normal);
                } else {
                    $preview = $ar['preview'];
                    $normal = $ar['normal'];
                    @unlink($path . $preview);
                    @unlink($path . $normal);
                    @unlink($path . 'nowatermark/' . $normal);
                }
                /* if(defined('STR_MEDIA') && STR_MEDIA=='new'){
                  $preview = $ar['preview'];
                  $normal = $ar['normal'];
                  @unlink(MEDIA_FOLDER.'/'.$preview);
                  @unlink(MEDIA_FOLDER.'/'.$normal);
                  $file_name_parts=explode('/', $normal);
                  $file_name=end($file_name_parts);
                  $file_name=preg_replace('/\.src\./', '.wtr.', $file_name);
                  array_pop($file_name_parts);
                  @unlink(MEDIA_FOLDER.'/'.implode('/', $file_name_parts));
                  }elseif(defined('STR_MEDIA') && STR_MEDIA=='semi'){
                  $preview = $ar['preview'];
                  $normal = $ar['normal'];
                  @unlink(MEDIA_FOLDER.'/'.$preview);
                  @unlink(MEDIA_FOLDER.'/'.$normal);
                  @unlink(MEDIA_FOLDER.'/nowatermark/'.$normal);
                  }else{
                  $preview = $ar['preview'];
                  $normal = $ar['normal'];
                  @unlink($path.$preview);
                  @unlink($path.$normal);
                  @unlink($path.'nowatermark/'.$normal);
                  } */
            }
        }
        return true;
    }

    /**
     * Get config value
     * @param string $key key
     * @return string
     */
    function getConfigValue($key) {
        if (!self::$config_loaded) {
            $this->loadConfig();
        }
        if (isset(self::$config_array[$key])) {
            return self::$config_array[$key];
        }
        return false;
    }

    function getAllConfigArray() {
        return self::$config_array;
    }

    /* function setConfigValue ( $key, $value ) {
      if ( !$this->config_loaded ) {
      $this->loadConfig();
      }
      $this->config_array[$key]=$value;
      } */

    /**
     * Get debug mode
     * @param void
     * @return boolean
     */
    function getDebugMode() {
        return DEBUG_MODE;
    }

    /**
     * Set debug mode 
     * @param boolean
     * @return void
     */
    function setDebugMode($debug_mode) {
        return;
    }

    function htmlspecialchars($value, $flags = '') {
        if ($flags == '') {
            $flags = ENT_COMPAT | ENT_HTML401;
        }
        if (is_array($value)) {
            if (count($value) > 0) {
                foreach ($value as $ak => $av) {
                    if (is_array($av)) {
                        $value[$ak] = $this->htmlspecialchars($av);
                    } else {
                        $value[$ak] = $this->escape(htmlspecialchars($av, $flags, SITE_ENCODING));
                    }
                }
            }
        } else {
            $value = $this->escape(htmlspecialchars($value, $flags, SITE_ENCODING));
        }
        return $value;
    }

    protected function restoreFavorites($user_id) {

        if (isset($_COOKIE['user_favorites']) && $_COOKIE['user_favorites'] != '') {
            $cc = unserialize($_COOKIE['user_favorites']);
        } else {
            $cc = array();
        }
        $cc[$user_id] = array();
        $DBC = DBC::getInstance();
        $query = 'SELECT id FROM ' . DB_PREFIX . '_userlists WHERE user_id=? AND lcode=?';
        $stmt = $DBC->query($query, array($user_id, 'fav'));

        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                $cc[$user_id][$ar['id']] = $ar['id'];
            }
        }

        setcookie('user_favorites', '', time() - 7 * 24 * 3600, '/', self::$_cookiedomain);
        setcookie('user_favorites', serialize($cc), time() + 7 * 24 * 3600, '/', self::$_cookiedomain);
        $_SESSION['favorites'] = $cc[$user_id];
        unset($cc);
    }

    function htmlspecialchars_decode($value, $flags = '') {
        if ($flags == '') {
            if (defined('ENT_HTML401')) {
                $flags = ENT_COMPAT | ENT_HTML401;
            } else {
                $flags = ENT_COMPAT;
            }
        }
        if (is_array($value)) {
            if (count($value) > 0) {
                foreach ($value as $ak => $av) {
                    if (is_array($av)) {
                        $value[$ak] = $this->htmlspecialchars_decode($av);
                    } else {
                        $value[$ak] = htmlspecialchars_decode($av, $flags);
                    }
                }
            }
        } else {
            $value = htmlspecialchars_decode($value, $flags);
        }
        return $value;
    }

    /**
     * Get value
     * @param string $key key
     * @return string
     */
    function getRequestValue($key, $type = '', $from = '') {
        $flags = ENT_COMPAT | ENT_HTML401;
        $value = NULL;
        switch ($from) {
            case 'get' : {
                    if (isset($_GET[$key])) {
                        $value = $this->escape($_GET[$key]);
                        $value = htmlspecialchars($_GET[$key], $flags, SITE_ENCODING);
                    }
                    break;
                }
            case 'post' : {
                    if (isset($_POST[$key])) {
                        $value = $this->escape($_POST[$key]);
                    }
                    break;
                }
            default : {
                    if (isset($_GET[$key])) {
                        $value = $_GET[$key];
                        //$value=$this->xssProtect($_GET[$key]);
                        //$value=strip_tags($_GET[$key]);
                        if (is_array($value)) {
                            $value = $this->htmlspecialchars($value, $flags);
                            /* foreach ($value as $k=>$v){
                              $value[$k]=htmlspecialchars($v);
                              } */
                        } else {

                            $value = htmlspecialchars($this->escape($value), $flags, SITE_ENCODING);
                            //$value=htmlspecialchars($value, $flags, SITE_ENCODING);
                        }
                    } elseif (isset($_POST[$key])) {

                        $value = $_POST[$key];
                        //echo '<pre>';
                        //echo $key;
                        //print_r($value);
                        if (is_array($value)) {
                            /* foreach ($value as $k=>$v){
                              $value[$k]=htmlspecialchars($v);
                              } */
                            $value = $this->htmlspecialchars($value, $flags);
                        } else {
                            $value = htmlspecialchars($this->escape($value), $flags, SITE_ENCODING);
                        }
                        //echo '</pre>';
                    }
                }
        }

        if ($value === NULL) {
            return $value;
        }

        if (!is_array($value)) {
            $value = trim($value);
            $value = $this->getSafeValue($value);
            if ($this->getConfigValue('sql_paranoid_mode')) {
                if (preg_match('/union/i', $value)) {
                    return NULL;
                }
                if (preg_match('/left\sjoin/i', $value)) {
                    return NULL;
                }

                if (preg_match('/sleep[\s]*\(/i', $value)) {
                    return NULL;
                }
                if (preg_match('/benchmark/i', $value)) {
                    return NULL;
                }

                if (preg_match_all('/select/i', $value, $matches)) {
                    if (count($matches[0]) > 1) {
                        return NULL;
                    }
                }
            }
            return $value;
        } elseif (is_array($value)) {
            $values = $value;
            foreach ($values as $k => $v) {
                if (!is_array($v)) {
                    $v = trim($v);
                    $v = $this->getSafeValue($v);
                    if (($v === '' || preg_match('/union/i', $v) || preg_match('/select/i', $v) || preg_match('/left\sjoin/i', $v) || preg_match('/sleep[\s]*\(/i', $v)) and $this->getConfigValue('sql_paranoid_mode')) {
                        unset($values[$k]);
                    } else {
                        $values[$k] = $v;
                    }
                }
            }
            if (count($values) == 0) {
                return array();
            } else {
                return $values;
            }
        }

        switch ($type) {
            case 'int' : {
                    if (!is_array($value)) {
                        $value = (int) $value;
                    } else {
                        $value = 0;
                    }

                    break;
                }
            case 'bool' : {
                    $value = (bool) $value;
                    break;
                }
            case 'float' : {
                    $value = preg_replace('/[^\d\.,]/', '', $value);
                    break;
                }
        }

        return $value;
    }

    private function xssProtect($value) {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = htmlspecialchars($v);
            }
        } else {
            $value = htmlspecialchars($value);
        }
        return $value;
    }

    private function getSafeValue($value) {
        return preg_replace('/(\/\*[^\/]*\*\/)/', '', $value);
    }

    /**
     * Set request value
     * @param string $key key
     * @param string $value value
     * @return void
     */
    function setRequestValue($key, $value) {
        $_REQUEST[$key] = $value;
        $_POST[$key] = $value;
        return;
    }

    /**
     * Rise error 
     * @param string $error error message
     * @return void
     */
    function riseError($error_message) {
        $this->error_message = $error_message;
        $this->error_state = true;
    }

    function clearError() {
        $this->error_message = '';
        $this->error_state = false;
    }

    /**
     * Get error 
     * @param void
     * @return boolean
     */
    function getError() {
        return $this->error_message;
    }

    /**
     * Get error message
     * @param void
     * @return string
     */
    function GetErrorMessage() {
        return $this->error_message;
    }

    /**
     * Write log message
     * @param string $message message
     * @return void
     */
    function writeLog($message) {
        ob_start(); 
        debug_print_backtrace(); 
        $trace = ob_get_contents(); 
        ob_end_clean();         
        $message.= '<hr>Stack trace<br><pre>'.$trace.'</pre>';
        
        if ($this->getConfigValue('apps.logger.enable') and file_exists(SITEBILL_DOCUMENT_ROOT . '/apps/logger/admin/admin.php')) {
            require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/object_manager.php');
            require_once (SITEBILL_DOCUMENT_ROOT . '/apps/logger/admin/admin.php');
            if (is_array($message)) {
                logger_admin::write_log($message);
            } else {
                $message_array = array('apps_name' => '', 'method' => '', 'message' => $message, 'type' => '');
                logger_admin::write_log($message_array);
            }
            return;
        }
        return;
    }

    function writeArrayLog($array) {
        $message = '<pre>' . var_export($array, true) . '</pre>';
        ob_start(); 
        debug_print_backtrace(); 
        $trace = ob_get_contents(); 
        ob_end_clean();         
        $message.= '<hr>Stack trace<br><pre>'.$trace.'</pre>';
        
        $this->writeLog($message);
    }

    /**
     * Get image list admin
     * @param string $action action
     * @param string $table_name table name
     * @param string $key key
     * @param int $record_id record id
     * @return string
     */
    function getImageListAdmin($action, $table_name, $key, $record_id, &$callback_count = NULL, $no_controls = false) {

        if (SITEBILL_MAIN_URL != '') {
            $url = SITEBILL_MAIN_URL . '/' . $this->storage_dir;
        } else {
            $url = $this->storage_dir;
        }

        $record_id = (int) $record_id;

        if ($record_id == 0) {
            return '';
        }


        //$query = "SELECT i.* FROM ".DB_PREFIX."_".$table_name."_image AS li, ".IMAGE_TABLE." AS i WHERE li.".$key."=$record_id AND li.image_id=i.image_id ORDER BY li.sort_order";
        $query = 'SELECT i.* FROM ' . DB_PREFIX . '_' . $table_name . '_image AS li, ' . IMAGE_TABLE . ' AS i WHERE li.' . $key . '=? AND li.image_id=i.image_id ORDER BY li.sort_order';
        $DBC = DBC::getInstance();
        $stmt = $DBC->query($query, array($record_id));
        if ($stmt) {
            $i = 0;
            $rs .= '<style>
    			.preview_admin { float: left; min-height: 250px; padding: 5px; margin: 5px; }
    			.preview_admin td > img { width: 100px; border: 1px solid #CFCFCF;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	border-radius: 5px;
	margin-bottom: 5px;}
    
    			</style>';

            $rs .= '<script type="text/javascript" src="' . SITEBILL_MAIN_URL . '/apps/system/js/dataimagelist.js?v=1"></script>';
            $rs .= '<script type="text/javascript">DataImagelist.attachDblclick();</script>';


            while ($ar = $DBC->fetch($stmt)) {

                $rs .= '<div class="preview_admin">
    		<table border="0" id="data_gallery">';

                if (isset($ar['title'])) {
                    $rs .= '<tr><td class="field_tab" style="height:20px; border: 1px solid gray;" alt="' . $ar['image_id'] . '">' . $ar['title'] . '<td></tr>';
                }
                if (isset($ar['description'])) {
                    $rs .= '<tr><td class="field_tab_description" style="height:20px; border: 1px solid gray;" alt="' . $ar['image_id'] . '">' . $ar['description'] . '<td></tr>';
                }


                $rs .= '<tr>
    		<td>
    		<br />
    		<img src="' . $url . '' . $ar['preview'] . '" border="0" align="left"/><br>
    		</td>';
                $rs .= '</tr>';

                $rs .= '<tr>';
                $rs .= '<td>';
                $rs .= '<a href="javascript:void(0);" onClick="DataImagelist.deleteImage(this,' . $ar['image_id'] . ',' . $record_id . ',\'' . $table_name . '\',\'' . $key . '\')"><img src="' . SITEBILL_MAIN_URL . '/apps/admin/admin/template/img/delete.png" width="16" border="0" alt="удалить" title="удалить"></a>
    		<a href="javascript:void(0);" onClick="DataImagelist.upImage(this,' . $ar['image_id'] . ',' . $record_id . ',\'' . $table_name . '\',\'' . $key . '\')"><img src="' . SITEBILL_MAIN_URL . '/img/up.gif" border="0" alt="наверх" title="наверх"></a>
    		<a href="javascript:void(0);" onClick="DataImagelist.downImage(this,' . $ar['image_id'] . ',' . $record_id . ',\'' . $table_name . '\',\'' . $key . '\')"><img src="' . SITEBILL_MAIN_URL . '/img/down1.gif" border="0" alt="вниз" title="вниз"></a>
    		<a href="javascript:void(0);" onClick="DataImagelist.makeMain(this,' . $ar['image_id'] . ',' . $record_id . ',\'' . $table_name . '\',\'' . $key . '\')">Сделать главной</a>
    		<!--<a href="javascript:void(0);" onClick="DataImagelist.rotateImage(this,' . $ar['image_id'] . ',' . $record_id . ',\'' . $table_name . '\',\'' . $key . '\', \'ccw\')"><img src="' . SITEBILL_MAIN_URL . '/apps/admin/admin/template/img/rotccw.png" border="0" alt="наверх" title="Повернуть против часовой стрелки"></a>
    		<a href="javascript:void(0);" onClick="DataImagelist.rotateImage(this,' . $ar['image_id'] . ',' . $record_id . ',\'' . $table_name . '\',\'' . $key . '\', \'cw\')"><img src="' . SITEBILL_MAIN_URL . '/apps/admin/admin/template/img/rotcw.png" border="0" alt="наверх" title="Повернуть по часовой стрелке"></a>-->
    				</td>
    		</tr>';

                $rs .= '</table>
    		</div>';
                //$rs .= '<div style="clear: both;"></div>';
                $i++;
            }
            if ($callback_count !== NULL) {
                $callback_count = $i;
            }
        }
        return $rs;
    }

    /**
     * Get file list admin
     * @param string $action action
     * @param string $table_name table name
     * @param string $key key
     * @param int $record_id record id
     * @return string
     */
    function getFileListAdmin($action, $table_name, $key, $record_id) {
        if (SITEBILL_MAIN_URL != '') {
            $url = SITEBILL_MAIN_URL . '/' . $this->storage_dir;
        } else {
            $url = $this->storage_dir;
        }
        $record_id = (int) $record_id;
        $DBC = DBC::getInstance();
        $query = 'SELECT i.* FROM ' . DB_PREFIX . '_' . $table_name . '_image AS li, ' . IMAGE_TABLE . ' AS i WHERE li.' . $key . '=? AND li.image_id=i.image_id ORDER BY li.sort_order';
        $stmt = $DBC->query($query, array($record_id));
        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                /* $up_link = '?action='.$action.'&do=edit&'.$key.'='.$record_id.'&subdo=up_image&image_id='.$ar['image_id'];
                  $down_link = '?action='.$action.'&do=edit&'.$key.'='.$record_id.'&subdo=down_image&image_id='.$ar['image_id'];


                  $up_link_img = '<a href="'.$up_link.'"><img src="'.SITEBILL_MAIN_URL.'/img/up.gif" border="0" alt="наверх" title="наверх"></a>';
                  $down_link_img = '<a href="'.$down_link.'"><img src="'.SITEBILL_MAIN_URL.'/img/down1.gif" border="0" alt="вниз" title="вниз"></a>';
                 */
                $delete_link = '?action=' . $action . '&do=edit&' . $key . '=' . $record_id . '&subdo=delete_image&image_id=' . $ar['image_id'];
                $rs .= '<div class="preview_admin" style="padding: 2px; border: 1px solid gray;">
    		<table border="0">
    		<tr>
    		<td>
    		<a href="' . $url . $ar['preview'] . '" target="_blank"><img src="/img/file.png" border="0" align="left"/> ' . $ar['preview'] . '</a><br>
    		</td>
    		<td>
    		<a href="' . $delete_link . '" onclick="return confirm(\'' . Multilanguage::_('L_MESSAGE_REALLY_WANT_DELETE') . '\');">' . Multilanguage::_('L_DELETE_LC') . '</a>
    		
    		</td>
    		</tr>
    		</table>
    		</div>';
                $rs .= '<div style="clear: both;"></div>';
            }
        }
        return $rs;
    }

    function get_page_links_list_default($page, $total, $per_page, $params) {
        if ($total <= $per_page) {
            return '';
        }
        if (isset($params['page_url']) && $params['page_url'] != '') {
            $url = SITEBILL_MAIN_URL . '/' . $params['page_url'];
            unset($params['page_url']);
        } else {
            $url = '';
        }
        $pairs = array();
        unset($params['page']);
        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                if (is_array($value)) {
                    if (count($value) > 0) {
                        foreach ($value as $v) {
                            if ($v != '') {
                                $pairs[] = $key . '[]=' . $v;
                            }
                        }
                    }
                } elseif ($value != '') {
                    $pairs[] = "$key=$value";
                }
            }
        }
        if (count($pairs) > 0) {
            $url = $url . '?' . implode('&', $pairs);
        } else {
            $url = $url;
        }

        $current_page = $page;
        if ($current_page == '') {
            $current_page = 1;
        } else {
            $current_page = (int) $current_page;
        }

        $limit = $per_page;

        $total_pages = ceil($total / $limit);
        $page_navigation = '';
        $first_page_navigation = '';
        $last_page_navigation = '';
        $start_page_navigation = '';
        $end_page_navigation = '';
        $p_prew = $current_page - 1;
        $p_next = $current_page + 1;

        $last_number_page = '<li><a rel="nofollow" href="' . $url . (false !== strpos($url, '?') ? '&page=' . $total_pages : '?page=' . $total_pages) . '" class="pagenav"><strong>' . $total_pages . '</strong></a></li>';

        if ($current_page == 1) {
            $first_page_navigation .= '<li><span class="pagenav">&laquo;&laquo; </span></li>';
        } else {
            $first_page_navigation .= '<li><a rel="nofollow" href="' . $url . (false !== strpos($url, '?') ? '&page=1' : '?page=1') . '" class="pagenav" title="в начало">&laquo;&laquo; </a></li>';
        }

        if ($current_page == $total_pages) {
            $last_page_navigation .= '<li><span class="pagenav"> &raquo;&raquo;</span></li>';
            $last_number_page = '';
        } else {
            $last_page_navigation .= '<li><a rel="nofollow" href="' . $url . (false !== strpos($url, '?') ? '&page=' . $total_pages : '?page=' . $total_pages) . '" class="pagenav" title="в конец"> &raquo;&raquo;</a></li>';
        }

        if ($p_prew < 1) {
            $start_page_navigation .= '<li><span class="pagenav">&laquo; </span></li>';
        } else {
            $start_page_navigation .= '<li><a rel="nofollow" href="' . $url . (false !== strpos($url, '?') ? '&page=' . $p_prew : '?page=' . $p_prew) . '" class="pagenav" title="предыдущая">&laquo; </a></li>';
        }

        if ($p_next > $total_pages) {
            $end_page_navigation .= '<li><span class="pagenav"> &raquo;</span></li>';
        } else {
            $end_page_navigation .= '<li><a rel="nofollow" href="' . $url . (false !== strpos($url, '?') ? '&page=' . $p_next : '?page=' . $p_next) . '" class="pagenav" title="следующая"> &raquo;</a></li>';
        }


        $linestart = $current_page - 7;
        $lineend = $current_page + 7;

        if ($linestart <= 1) {
            $linestart = 1;
            $lineprefix = '';
        } else {
            $lineprefix = '<li>...</li>';
        }

        if ($lineend >= $total_pages) {
            $lineend = $total_pages;
            $last_number_page = '';
            $linepostfix = '';
        } else {
            $linepostfix = '<li>...</li>';
        }

        for ($i = $linestart; $i <= $lineend; $i++) {
            if ($current_page == $i) {
                $page_navigation .= '<li><span class="pagenav"> ' . $i . ' </span></li>';
            } else {
                $page_navigation .= '<li><a rel="nofollow" href="' . $url . (false !== strpos($url, '?') ? '&page=' . $i : '?page=' . $i) . '" class="pagenav"><strong>' . $i . '</strong></a></li>';
            }
        }
        $page_navigation = '<ul class="pagination">' . $first_page_navigation . $start_page_navigation . $lineprefix . $page_navigation . $linepostfix . $end_page_navigation . $last_number_page . $last_page_navigation . '</ul>';
        return $page_navigation;
    }

    /**
     * Get page links list
     * @param int $cur_page current page number
     * @param int $total 
     * @param int $per_page
     * @param array $params
     * @return array
     */
    function get_page_links_list($page, $total, $per_page, $params) {

        if (defined('ADMIN_MODE')) {
            return $this->get_page_links_list_default($page, $total, $per_page, $params);
        }

        $pager_settings = array();
        $pager_settings['draw_all_pages'] = intval($this->getConfigValue('core.listing.pager_draw_all'));
        $pager_settings['draw_all_pages_max'] = intval($this->getConfigValue('core.listing.pager_draw_all_max'));
        $pager_settings['active_page_offset'] = intval($this->getConfigValue('core.listing.pager_page_offset'));
        $pager_settings['show_end_links'] = intval($this->getConfigValue('core.listing.pager_end_buttons'));
        $pager_settings['show_prev_links'] = intval($this->getConfigValue('core.listing.pager_prev_buttons'));
        $pager_settings['show_prefixes'] = intval($this->getConfigValue('core.listing.pager_show_prefixes'));

        if ($total <= $per_page) {
            return '';
        }

        if (isset($params['page_url']) && $params['page_url'] != '') {
            $url = SITEBILL_MAIN_URL . '/' . $params['page_url'] . '/?';
        } else {
            $url = SITEBILL_MAIN_URL . '/?';
            $url = '?';
        }
        unset($params['page_url']);
        unset($params['page']);

        /* $pairs=array();
          if(count($params)>0){
          //echo urldecode(http_build_query($params));
          foreach ( $params as $key => $value ) {
          if(is_array($value)){
          if(count($value)>0){
          foreach($value as $v){
          if($v!=''){
          $pairs[] = $key.'[]='.$v;
          }
          }
          }
          }elseif ( $value != '' ) {
          $pairs[] = $key."=".$value;
          }
          }
          }
          if(!empty($pairs)){
          $pager_params_string=implode('&', $pairs);
          }else{
          $pager_params_string='';
          } */

        if (count($params) > 0) {
            $pager_params_string = urldecode(http_build_query($params));
        } else {
            $pager_params_string = '';
        }


        $current_page = $page;
        if ($current_page == '') {
            $current_page = 1;
        } else {
            $current_page = (int) $current_page;
        }

        $limit = $per_page;

        $total_pages = ceil($total / $limit);
        if ($total_pages <= $pager_settings['draw_all_pages_max']) {
            $pager_settings['draw_all_pages'] = 1;
        }
        $pages_count = ceil($total / $limit);
        if ($total_pages < 2) {
            return '';
        }

        $ret = array();

        $p_prew = $current_page - 1;
        $p_next = $current_page + 1;

        if ($current_page == 1) {
            $fpn['text'] = '&laquo;&laquo;';
            $fpn['href'] = $url . 'page=1' . ($pager_params_string != '' ? '&' . $pager_params_string : '');
        } else {
            $fpn['text'] = '&laquo;&laquo;';
            $fpn['href'] = $url . 'page=1' . ($pager_params_string != '' ? '&' . $pager_params_string : '');
        }

        $ret['fpn'] = $fpn;

        if ($current_page == $total_pages) {
            $lpn['text'] = '&raquo;&raquo;';
            $lpn['href'] = '';
        } else {
            $lpn['text'] = '&raquo;&raquo;';
            $lpn['href'] = $url . 'page=' . $total_pages . ($pager_params_string != '' ? '&' . $pager_params_string : '');
        }

        $ret['lpn'] = $lpn;

        if ($p_prew < 1) {
            $ppn['text'] = '&laquo;';
            $ppn['href'] = '';
        } else {
            $ppn['text'] = '&laquo;';
            $ppn['href'] = $url . 'page=' . $p_prew . ($pager_params_string != '' ? '&' . $pager_params_string : '');
            $ppn['go_page'] = $p_prew;
        }

        $ret['ppn'] = $ppn;

        if ($p_next > $total_pages) {
            $npn['text'] = '&raquo;';
            $npn['href'] = '';
        } else {
            $npn['text'] = '&raquo;';
            $npn['href'] = $url . 'page=' . $p_next . ($pager_params_string != '' ? '&' . $pager_params_string : '');
            $npn['go_page'] = $p_next;
        }

        $ret['npn'] = $npn;

        $start_page = $current_page - $pager_settings['active_page_offset'];
        $end_page = $current_page + $pager_settings['active_page_offset'];

        if ($start_page <= 1) {
            $pager_settings['left_prefix'] = 0;
            $pager_settings['start'] = 1;
        } else {
            $pager_settings['left_prefix'] = 0;
            if ($pager_settings['show_prefixes'] == 1) {
                $pager_settings['left_prefix'] = 1;
            }
            $pager_settings['start'] = $start_page;
        }

        if ($end_page >= $total_pages) {
            $pager_settings['right_prefix'] = 0;
            $pager_settings['end'] = $total_pages;
        } else {
            $pager_settings['right_prefix'] = 0;
            if ($pager_settings['show_prefixes'] == 1) {
                $pager_settings['right_prefix'] = 1;
            }
            $pager_settings['end'] = $end_page;
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $current_page) {
                $ret['pages'][$i] = array('text' => $i, 'href' => '', 'current' => '1');
            } else {
                $ret['pages'][$i] = array('text' => $i, 'href' => $url . 'page=' . $i . ($pager_params_string != '' ? '&' . $pager_params_string : ''), 'current' => '0');
            }
        }

        $ret['current_page'] = $current_page;
        $ret['total_pages'] = $total_pages;

        global $smarty;
        $smarty->assign('pager_settings', $pager_settings);
        $smarty->assign('paging', $ret);
        $tpl = SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . '/common_pager.tpl';
        if (!file_exists($tpl)) {
            $tpl = SITEBILL_DOCUMENT_ROOT . '/apps/system/template/common_pager.tpl';
        }
        return $smarty->fetch($tpl);
    }

    /* function __get_page_links_list ($page, $total, $per_page, $params ) {

      $show_all_pages=false;
      $show_nav_buttons=true;
      $show_last_buttons=true;
      $page_offset_active=7;
      $noindex_pager=true;

      $pager_wrap_tpl='<ul class="pagination">{links}</ul>';
      $sep_tpl='<li><a href="#">...</a></li>';
      $page_tpl='<li><a rel="nofollow" href="{href}" class="pagenav">{page}</a></li>';
      $page_active_tpl='<li><span class="pagenav">{page}</span></li>';

      $next_active_tpl='<li><a rel="nofollow" href="{href}" class="pagenav"><strong>&raquo;</strong></a></li>';
      $next_passive_tpl='<li><a rel="nofollow" href="{href}" class="pagenav"><strong>&raquo;</strong></a></li>';

      $prev_active_tpl='<li><a rel="nofollow" href="{href}" class="pagenav"><strong>&laquo;</strong></a></li>';
      $prev_passive_tpl='<li><a rel="nofollow" href="{href}" class="pagenav"><strong>&laquo;</strong></a></li>';

      $first_active_tpl='<li><a rel="nofollow" href="{href}" class="pagenav"><strong>&laquo;&laquo;</strong></a></li>';
      $first_passive_tpl='<li><a rel="nofollow" href="{href}" class="pagenav"><strong>&laquo;&laquo;</strong></a></li>';

      $last_active_tpl='<li><a rel="nofollow" href="{href}" class="pagenav"><strong>&raquo;&raquo;</strong></a></li>';
      $last_passive_tpl='<li><a rel="nofollow" href="{href}" class="pagenav"><strong>&raquo;&raquo;</strong></a></li>';

      $page_first_tpl='<li><a rel="nofollow" href="{href}" class="pagenav">{page}</a></li>';
      $page_last_tpl='<li><a rel="nofollow" href="{href}" class="pagenav">{page}</a></li>';

      if(!defined('ADMIN_MODE')){
      $pager_wrap_tpl='<ul class="pagination">{links}</ul>';
      $sep_tpl='<li><a href="javascript:void(0);">...</a></li>';
      $page_tpl='<li><a href="{href}">{page}</a></li>';
      $page_active_tpl='<li class="active"><a href="{href}">{page}</a></li>';
      $next_active_tpl='<li><a href="{href}">›</a></li>';
      $next_passive_tpl='<li><a href="{href}">›</a></li>';
      $prev_active_tpl='<li><a href="{href}">‹</a></li>';
      $prev_passive_tpl='<li><a href="{href}">‹</a></li>';

      }


      if ( $total <= $per_page ) {
      return '';
      }

      $current_page=intval($page);
      if($current_page==0){
      $current_page=1;
      }

      $total_pages=ceil($total/$per_page);

      if($show_all_pages){
      $linestart=1;
      $lineend=$total_pages;
      }else{
      //$showable_pages_count=2*$page_offset_active+1;
      $linestart=$current_page-$page_offset_active;
      $lineend=$current_page+$page_offset_active;

      if($linestart<=1){
      $linestart=1;
      }

      if($lineend>=$total_pages){
      $lineend=$total_pages;
      }
      }


      if(isset($params['page_url']) && $params['page_url']!=''){
      $url=SITEBILL_MAIN_URL.'/'.$params['page_url'];
      unset($params['page_url']);
      }else{
      $url='';
      }

      $pairs=array();
      $query_str='';
      unset($params['page']);
      if(count($params)>0){
      foreach ( $params as $key => $value ) {
      if(is_array($value)){
      if(count($value)>0){
      foreach($value as $v){
      if($v!=''){
      $pairs[] = $key.'[]='.$v;
      }
      }
      }
      }elseif ( $value != '' ) {
      $pairs[]=$key.'='.$value;
      }
      }
      }
      if(count($pairs)>0){
      $query_str='?'.implode('&', $pairs).'&';
      }else{
      $query_str='?';
      }

      $fpn_href=$url;
      $lpn_href=$url;
      $ppn_href=$url;
      $npn_href=$url;
      $page_href=$url.$query_str;






      $page_navigation='';
      $first_page_navigation='';
      $last_page_navigation='';
      $start_page_navigation='';
      $end_page_navigation='';

      $p_prew=$current_page-1;
      $p_next=$current_page+1;
      $fpn='';
      $lpn='';
      $ppn='';
      $npn='';


      if(count($pairs)>0){
      $fpn_href=$fpn_href.'?'.implode('&', $pairs).'&page=1';
      $lpn_href=$lpn_href.'?'.implode('&', $pairs).'&page='.$total_pages;
      $ppn_href=$ppn_href.'?'.implode('&', $pairs).'&page='.$p_prew;
      $npn_href=$npn_href.'?'.implode('&', $pairs).'&page='.$p_next;
      //$page_href=$page_href.'?'.implode('&', $pairs).'&page='.$p_next;
      }else{
      $fpn_href=$fpn_href.'?page=1';
      $lpn_href=$lpn_href.'?page='.$total_pages;
      $ppn_href=$ppn_href.'?page='.$p_prew;
      $npn_href=$npn_href.'?page='.$p_next;
      }

      if($show_last_buttons){
      if($current_page!=1 && $linestart!=1){
      $fpn=str_replace('{href}', $fpn_href, $first_active_tpl);
      }
      if($current_page!=$total_pages && $lineend!=$total_pages){
      $lpn=str_replace('{href}', $lpn_href, $last_active_tpl);
      }
      }

      if($show_nav_buttons){
      if($current_page!=1){
      $ppn=str_replace('{href}', $ppn_href, $prev_active_tpl);
      }
      if($current_page!=$total_pages){
      $npn=str_replace('{href}', $npn_href, $next_active_tpl);
      }
      }

      if($linestart!=1){
      $first_page_go=str_replace(array('{href}', '{page}'), array($fpn_href, 1), $page_first_tpl);
      }

      if($linestart!=1 && ($linestart-1)==1){
      $lineprefix='';
      }elseif($linestart!=1 && ($linestart-1)>1){
      $lineprefix=$sep_tpl;
      }
      for($i=$linestart;$i<=$lineend;$i++){
      if($current_page==$i){
      $page_navigation.=str_replace(array('{href}', '{page}'), array($page_href.'page='.$i, $i), $page_active_tpl);
      }else{
      $page_navigation.=str_replace(array('{href}', '{page}'), array($page_href.'page='.$i, $i), $page_tpl);
      }
      }

      if($lineend!=$total_pages){
      $last_page_go=str_replace(array('{href}', '{page}'), array($lpn_href, $total_pages), $page_last_tpl);
      }

      if($lineend!=$total_pages && ($total_pages-$lineend)==1){
      $linepostfix='';
      }elseif($lineend!=$total_pages && ($total_pages-$lineend)>1){
      $linepostfix=$sep_tpl;
      }


      $page_nav=str_replace('{links}', $fpn.$ppn.$first_page_go.$lineprefix.$page_navigation.$linepostfix.$last_page_go.$npn.$lpn, $pager_wrap_tpl);
      if($noindex_pager){
      $page_nav='<!--noindex-->'.$page_nav.'<!--/noindex-->';
      }
      return $page_nav;
      } */

    /**
     * Get image list admin
     * @param string $action action
     * @param string $table_name table name
     * @param string $key key
     * @param int $record_id record id
     * @param int $limit limit value
     * @return string
     */
    function get_image_array($action, $table_name, $key, $record_id, $limit = 0) {
        return array();
        //устаревший метод
        //return false;
        /*
          $DBC = DBC::getInstance();
          $url = $this->storage_dir;
          $ra = array();
          $record_id = (int) $record_id;
          $query = 'SELECT i.* FROM ' . DB_PREFIX . '_' . $table_name . '_image AS li, ' . IMAGE_TABLE . ' AS i WHERE li.' . $key . '=? AND li.image_id=i.image_id ORDER BY li.sort_order';

          if ($limit > 0) {
          $query .= ' LIMIT ?';
          }


          if ($limit > 0) {
          $stmt = $DBC->query($query, array($record_id, $limit));
          } else {
          $stmt = $DBC->query($query, array($record_id));
          }

          if ($stmt) {
          $i = 0;
          while ($ar = $DBC->fetch($stmt)) {
          $ra[$i]['preview'] = $ar['preview'];
          $ra[$i]['normal'] = $ar['normal'];

          $ra[$i]['title'] = $ar['title'];
          $ra[$i]['description'] = $ar['description'];

          $ra[$i]['img_preview'] = $url . '' . $ar['preview'];
          $ra[$i]['img_normal'] = $url . '' . $ar['normal'];
          $i++;
          }
          }

          return $ra;
         * 
         */
    }

    /**
     * Get category breadcrumbs
     * @param array $params
     * @param array $category_structure
     * @param string $url
     * @return string
     */
    function get_category_breadcrumbs($params, $category_structure, $url = '') {
        $rs = '';


        if (!isset($params['topic_id']) || is_array($params['topic_id'])) {
            return $rs;
        }

        if ((int) $params['topic_id'] == 0) {
            return $rs;
        }
        if (!isset($category_structure['catalog'][$params['topic_id']])) {
            return $rs;
        }



        //foreach ( $category_structure['childs'][0] as $item_id => $catalog_id ) {
        if ($category_structure['catalog'][$params['topic_id']]['url'] != '') {
            $ra[] = '<a href="' . rtrim($url, '/') . '/' . $category_structure['catalog'][$params['topic_id']]['url'] . (false === strpos($category_structure['catalog'][$params['topic_id']]['url'], '.') ? self::$_trslashes : '') . '">' . $category_structure['catalog'][$params['topic_id']]['name'] . '</a>';
        } else {
            $ra[] = '<a href="' . rtrim($url, '/') . '/topic' . $params['topic_id'] . '.html">' . $category_structure['catalog'][$params['topic_id']]['name'] . '</a>';
        }

        $parent_category_id = $category_structure['catalog'][$params['topic_id']]['parent_id'];
        while ($category_structure['catalog'][$parent_category_id]['parent_id'] != 0) {
            if ($j++ > 100) {
                return;
            }
            if (isset($category_structure['catalog'][$parent_category_id]) && $category_structure['catalog'][$parent_category_id]['url'] != '') {
                $ra[] = '<a href="' . rtrim($url, '/') . '/' . $category_structure['catalog'][$parent_category_id]['url'] . (false === strpos($category_structure['catalog'][$parent_category_id]['url'], '.') ? self::$_trslashes : '') . '">' . $category_structure['catalog'][$parent_category_id]['name'] . '</a>';
            } else {
                $ra[] = '<a href="' . rtrim($url, '/') . '/topic' . $parent_category_id . '.html">' . $category_structure['catalog'][$parent_category_id]['name'] . '</a>';
            }
            $parent_category_id = $category_structure['catalog'][$parent_category_id]['parent_id'];
        }
        if (isset($category_structure['catalog'][$parent_category_id]) && $category_structure['catalog'][$parent_category_id]['name'] != '') {
            if ($category_structure['catalog'][$parent_category_id]['url'] != '') {
                $ra[] = '<a href="' . rtrim($url, '/') . '/' . $category_structure['catalog'][$parent_category_id]['url'] . (false === strpos($category_structure['catalog'][$parent_category_id]['url'], '.') ? self::$_trslashes : '1') . '">' . $category_structure['catalog'][$parent_category_id]['name'] . '</a>';
            } else {
                $ra[] = '<a href="' . rtrim($url, '/') . '/topic' . $parent_category_id . '.html">' . $category_structure['catalog'][$parent_category_id]['name'] . '</a>';
            }
        }
        if (Multilanguage::is_set('LT_BC_HOME', '_template')) {
            $ra[] = '<a href="' . SITEBILL_MAIN_URL . '/">' . Multilanguage::_('LT_BC_HOME', '_template') . '</a>';
        } else {
            $ra[] = '<a href="' . SITEBILL_MAIN_URL . '/">' . Multilanguage::_('L_HOME') . '</a>';
        }
        //$ra[]='<a href="'.SITEBILL_MAIN_URL.'/">'.Multilanguage::_('L_HOME').'</a>';
        $breadcrumbs_array = array_reverse($ra);
        $rs = implode(' / ', $breadcrumbs_array);
        
        $this->template->assert('breadcrumbs_array', $breadcrumbs_array);
        
        return $rs;
    }
    /*
     * тестовая функция для кастомизации крошек
     */
    function get_category_breadcrumbs_test($params, $category_structure, $url = '') {
        $rs = '';
        $bc_array=array();

        if (!isset($params['topic_id']) || is_array($params['topic_id'])) {
            return $rs;
        }

        if ((int) $params['topic_id'] == 0) {
            return $rs;
        }
        if (!isset($category_structure['catalog'][$params['topic_id']])) {
            return $rs;
        }



        //foreach ( $category_structure['childs'][0] as $item_id => $catalog_id ) {
        if ($category_structure['catalog'][$params['topic_id']]['url'] != '') {
            $ra[] = '<a href="' . rtrim($url, '/') . '/' . $category_structure['catalog'][$params['topic_id']]['url'] . (false === strpos($category_structure['catalog'][$params['topic_id']]['url'], '.') ? self::$_trslashes : '') . '">' . $category_structure['catalog'][$params['topic_id']]['name'] . '</a>';
            $bc_array[]=array(
                'href'=>SITEBILL_MAIN_URL . '/' . $category_structure['catalog'][$params['topic_id']]['url'] .(false===strpos($category_structure['catalog'][$params['topic_id']]['url'], '.') ? self::$_trslashes : ''),
                'name'=>$category_structure['catalog'][$params['topic_id']]['name']
            );
            
        } else {
            $ra[] = '<a href="' . rtrim($url, '/') . '/topic' . $params['topic_id'] . '.html">' . $category_structure['catalog'][$params['topic_id']]['name'] . '</a>';
            $bc_array[]=array(
                'href'=>SITEBILL_MAIN_URL . '/topic' . $params['topic_id'] . '.html',
                'name'=>$category_structure['catalog'][$params['topic_id']]['name']
            );
        }

        $parent_category_id = $category_structure['catalog'][$params['topic_id']]['parent_id'];
        while ($category_structure['catalog'][$parent_category_id]['parent_id'] != 0) {
            if ($j++ > 100) {
                return;
            }
            if (isset($category_structure['catalog'][$parent_category_id]) && $category_structure['catalog'][$parent_category_id]['url'] != '') {
                $ra[] = '<a href="' . rtrim($url, '/') . '/' . $category_structure['catalog'][$parent_category_id]['url'] . (false === strpos($category_structure['catalog'][$parent_category_id]['url'], '.') ? self::$_trslashes : '') . '">' . $category_structure['catalog'][$parent_category_id]['name'] . '</a>';
                $bc_array[]=array(
                    'href'=>SITEBILL_MAIN_URL .  '/' . $category_structure['catalog'][$parent_category_id]['url'].(false===strpos($category_structure['catalog'][$parent_category_id]['url'], '.') ? self::$_trslashes : ''),
                    'name'=>$category_structure['catalog'][$parent_category_id]['name']
                );
            } else {
                $ra[] = '<a href="' . rtrim($url, '/') . '/topic' . $parent_category_id . '.html">' . $category_structure['catalog'][$parent_category_id]['name'] . '</a>';
                $bc_array[]=array(
                    'href'=>SITEBILL_MAIN_URL .  '/topic' . $parent_category_id . '.html',
                    'name'=>$category_structure['catalog'][$parent_category_id]['name']
                );
            }
            $parent_category_id = $category_structure['catalog'][$parent_category_id]['parent_id'];
        }
        if (isset($category_structure['catalog'][$parent_category_id]) && $category_structure['catalog'][$parent_category_id]['name'] != '') {
            if ($category_structure['catalog'][$parent_category_id]['url'] != '') {
                $ra[] = '<a href="' . rtrim($url, '/') . '/' . $category_structure['catalog'][$parent_category_id]['url'] . (false === strpos($category_structure['catalog'][$parent_category_id]['url'], '.') ? self::$_trslashes : '1') . '">' . $category_structure['catalog'][$parent_category_id]['name'] . '</a>';
                $bc_array[]=array(
                    'href'=>SITEBILL_MAIN_URL .  '/' . $category_structure['catalog'][$parent_category_id]['url'].(false===strpos($category_structure['catalog'][$parent_category_id]['url'], '.') ? self::$_trslashes : ''),
                    'name'=>$category_structure['catalog'][$parent_category_id]['name']
                );
                
            } else {
                $ra[] = '<a href="' . rtrim($url, '/') . '/topic' . $parent_category_id . '.html">' . $category_structure['catalog'][$parent_category_id]['name'] . '</a>';
                $bc_array[]=array(
                    'href'=>SITEBILL_MAIN_URL .  '/topic' . $parent_category_id . '.html',
                    'name'=>$category_structure['catalog'][$parent_category_id]['name']
                );
                
            }
        }
        if (Multilanguage::is_set('LT_BC_HOME', '_template')) {
            $ra[] = '<a href="' . SITEBILL_MAIN_URL . '/">' . Multilanguage::_('LT_BC_HOME', '_template') . '</a>';
            $bc_array[]=array(
                'href'=>SITEBILL_MAIN_URL . '/',
                'name'=>Multilanguage::_('LT_BC_HOME', '_template')
            );
        } else {
            $ra[] = '<a href="' . SITEBILL_MAIN_URL . '/">' . Multilanguage::_('L_HOME') . '</a>';
            $bc_array[]=array(
                'href'=>SITEBILL_MAIN_URL . '/',
                'name'=>Multilanguage::_('L_HOME')
            );
        }
        $bc_array= array_reverse($bc_array);
        //print_r($bc_array);
        //$ra[]='<a href="'.SITEBILL_MAIN_URL.'/">'.Multilanguage::_('L_HOME').'</a>';
        $rs = implode(' / ', array_reverse($ra));
        return $rs;
    }

    /**
     * Get category breadcrumbs
     * @param array $params
     * @param array $category_structure
     * @param string $url
     * @return string
     */
    function get_category_breadcrumbs_string($params, $category_structure, $url = '') {
        $rs = '';
        $ra = array();
        $parent_category_id = 0;
        $j = 0;
        if (isset($category_structure['catalog'][$params['topic_id']])) {
            $ra[] = '' . $category_structure['catalog'][$params['topic_id']]['name'] . '';
            $parent_category_id = $category_structure['catalog'][$params['topic_id']]['parent_id'];
        }


        while (isset($category_structure['catalog'][$parent_category_id]['parent_id']) && $category_structure['catalog'][$parent_category_id]['parent_id'] != 0) {
            if ($j++ > 100) {
                return;
            }
            $ra[] = '' . $category_structure['catalog'][$parent_category_id]['name'] . '';
            $parent_category_id = $category_structure['catalog'][$parent_category_id]['parent_id'];
        }
        if (isset($category_structure['catalog'][$parent_category_id]['name']) && $category_structure['catalog'][$parent_category_id]['name'] != '') {
            $ra[] = '' . $category_structure['catalog'][$parent_category_id]['name'] . '';
        }
        $this->set_breadcrumbs_array(array_reverse($ra));
        $rs = implode(' / ', array_reverse($ra));
        return $rs;
    }

    function set_breadcrumbs_array($breadcrumbs_array = array()) {
        $this->breadcrumbs_array = $breadcrumbs_array;
    }

    function get_breadcrumbs_array() {
        return $this->breadcrumbs_array;
    }

    public function go301($new_location) {
        $sapi_name = php_sapi_name();
        if ($sapi_name == 'cgi' || $sapi_name == 'cgi-fcgi') {
            header('Status: 301 Moved Permanently');
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
        }
        header('Location: ' . $new_location);
        exit();
    }

    /**
     * Make preview
     * @param
     * @return
     */
    function makePreview($src, $dst, $width, $height, $ext = 'jpg', $md = 0, $final_ext = '') {
        $dst_info = pathinfo($dst);
        
        if ( !is_file($src) or empty($dst_info['extension']) ) {
            return false;
        }
        $source_img = false;
        if ($ext == 'jpg' || $ext == 'jpeg') {
            $source_img = @ImageCreateFromJPEG($src);
        } elseif ($ext == 'png') {
            $source_img = @ImageCreateFromPNG($src);
        } elseif ($ext == 'gif') {
            $source_img = @ImageCreateFromGIF($src);
        } elseif ($ext == 'webp') {
            $source_img = @ImageCreateFromWebp($src);
        }

        if ($source_img === false) {
            return false;
        }

        $w_src = imagesx($source_img);
        $h_src = imagesy($source_img);
        if ($w_src > $h_src) {
            $mode = 'width';
        } else {
            $mode = 'height';
        }
        if ($md == 'height') {
            $mode = 'height';
        }
        if ($md == 'width') {
            $mode = 'width';
        }
        if ($md == 'smart') {
            $mode = 'smart';
        }
        if ($md == 'c' || $md == 'f') {
            $mode = $md;
        }

        if ($mode == 'smart' || $mode == 'c') {
            $source_width = $w_src;
            $source_height = $h_src;

            $dest_width = $width;
            $dest_height = $height;

            $width_proportion = $source_width / $dest_width;
            $height_proportion = $source_height / $dest_height;

            if ($width_proportion < $height_proportion) {
                $common_proportion = $width_proportion;
            } else {
                $common_proportion = $height_proportion;
            }

            $equal_width = $dest_width * $common_proportion;
            $equal_height = $dest_height * $common_proportion;


            $width_offset = intval(($source_width - $equal_width) / 2);
            $height_offset = intval(($source_height - $equal_height) / 2);

            $tmp_img = imageCreateTrueColor($dest_width, $dest_height);
            imageAlphaBlending($tmp_img, false);
            imageSaveAlpha($tmp_img, true);
            imageCopyResampled($tmp_img, $source_img, 0, 0, $width_offset, $height_offset, $dest_width, $dest_height, ($equal_width), ($equal_height));
        } elseif ($mode == 'f') {
            $source_width = $w_src;
            $source_height = $h_src;

            $dest_width = $width;
            $dest_height = $height;



            $width_proportion = $source_width / $dest_width;
            $height_proportion = $source_height / $dest_height;

            if ($width_proportion > $height_proportion) {
                $common_proportion = $width_proportion;
            } else {
                $common_proportion = $height_proportion;
            }

            $equal_width = $source_width / $common_proportion;
            $equal_height = $source_height / $common_proportion;

            $width_offset = intval(($dest_width - $equal_width) / 2);
            $height_offset = intval(($dest_height - $equal_height) / 2);

            $tmp_img = imageCreateTrueColor($dest_width, $dest_height);
            imageAlphaBlending($tmp_img, false);

            //$white = imagecolorallocate($f, 255,255,255);
            //imagecolortransparent($f, $white);
            //$trans_colour = imagecolorallocate($tmp_img, 255, 255, 255);
            $trans_colour = imagecolorallocatealpha($tmp_img, 255, 255, 255, 127);
            imagefill($tmp_img, 0, 0, $trans_colour);
            imageCopyResampled($tmp_img, $source_img, $width_offset, $height_offset, 0, 0, $equal_width, $equal_height, $source_width, $source_height);
            imageSaveAlpha($tmp_img, true);
        } else {
            $ratio = 1;
            if ($mode == 'width') {
                if ($w_src > $width) {
                    $ratio = $w_src / $width;
                }
            } else {
                $tmp = $width;
                $width = $height;
                $height = $tmp;
                if ($h_src > $height) {
                    $ratio = $h_src / $height;
                }
            }
            $width_tmp = intval($w_src / $ratio);
            $height_tmp = intval($h_src / $ratio);
            $tmp_img = imageCreateTrueColor($width_tmp, $height_tmp);
            imageAlphaBlending($tmp_img, false);
            imageSaveAlpha($tmp_img, true);
            imageCopyResampled($tmp_img, $source_img, 0, 0, 0, 0, $width_tmp, $height_tmp, $w_src, $h_src);
        }

        if ($final_ext != '') {
            if ($final_ext == 'jpg' || $final_ext == 'jpeg') {
                imagejpeg($tmp_img, $dst, (int) $this->getConfigValue('jpeg_quality'));
            } elseif ($final_ext == 'png') {
                imagepng($tmp_img, $dst, (int) $this->getConfigValue('png_quality'));
            } elseif ($final_ext == 'gif') {
                imagegif($tmp_img, $dst);
            } elseif ($final_ext == 'webp') {
                imagewebp($tmp_img, $dst);
            }
        } else {
            if ($ext == 'jpg' || $ext == 'jpeg') {
                imagejpeg($tmp_img, $dst, (int) $this->getConfigValue('jpeg_quality'));
            } elseif ($ext == 'png') {
                imagepng($tmp_img, $dst, (int) $this->getConfigValue('png_quality'));
            } elseif ($ext == 'gif') {
                imagegif($tmp_img, $dst);
            } elseif ($ext == 'webp') {
                imagewebp($tmp_img, $dst);
            }
        }

        ImageDestroy($source_img);
        ImageDestroy($tmp_img);
        // ImageDestroy($preview_img);
        return array($width, $height);
    }

    /**
     * Make move 
     * @param
     * @return
     */
    function makeMove($src, $dst) {
        @rename($src, $dst);
    }

    /**
     * return id of Admininstrator 
     * @param
     * @return int
     */
    function getAdminUserId() {
        if (isset(self::$storage['AdminUserId'])) {
            return self::$storage['AdminUserId'];
        }
        $admin_id = 0;
        $DBC = DBC::getInstance();
        $query = 'SELECT u.user_id FROM ' . DB_PREFIX . '_user u LEFT JOIN ' . DB_PREFIX . '_group g USING(group_id) WHERE g.system_name=? LIMIT 1';
        $stmt = $DBC->query($query, array('admin'));
        if ($stmt) {
            $ar = $DBC->fetch($stmt);
            $admin_id = $ar['user_id'];
            self::$storage['AdminUserId'] = $admin_id;
        }
        return $admin_id;
    }

    /**
     * return Vendor info 
     * @param id integer
     * @return string
     */
    function getVendorInfoById($id) {
        $vendor_info = array();
        $DBC = DBC::getInstance();
        $query = 'SELECT * FROM ' . DB_PREFIX . '_vendor WHERE vendor_id=? LIMIT 1';
        $stmt = $DBC->query($query, array($id));
        if ($stmt) {
            $ar = $DBC->fetch($stmt);
            $vendor_info = $ar['user_id'];
        }
        return $vendor_info;
    }

    function getUnregisteredUserId() {
        $user_id = 0;
        /* if(0!=(int)$this->getConfigValue('free_advs_user_id')){
          return (int)$this->getConfigValue('free_advs_user_id');
          } */
        $DBC = DBC::getInstance();
        $query = 'SELECT user_id FROM ' . DB_PREFIX . '_user WHERE login=? LIMIT 1';
        $stmt = $DBC->query($query, array('_unregistered'));
        if ($stmt) {
            $ar = $DBC->fetch($stmt);
            $user_id = $ar['user_id'];
        }
        return $user_id;
    }

    function growCounter($table_name, $primary_key_name, $primary_key_value, $user_id = 0) {
        if (1 == $this->getConfigValue('use_realty_view_counter')) {
            if (!isset($_SESSION['realty_views'][$primary_key_value])) {
                $DBC = DBC::getInstance();
                $ocount = 0;
                $query = 'SELECT `view_count` FROM ' . DB_PREFIX . '_' . $table_name . ' WHERE ' . $primary_key_name . '=? LIMIT 1';
                $stmt = $DBC->query($query, array($primary_key_value));
                if ($stmt) {
                    $ar = $DBC->fetch($stmt);
                    $ocount = intval($ar['view_count']);
                }
                $ocount++;
                $query = 'UPDATE ' . DB_PREFIX . '_' . $table_name . ' SET view_count=? WHERE ' . $primary_key_name . '=?';
                $stmt = $DBC->query($query, array($ocount, $primary_key_value));
            }
            $_SESSION['realty_views'][$primary_key_value] = time();
        }
    }

    function validateEmailFormat($email) {
        if (preg_match('/^[0-9a-z]+[-\._0-9a-z]*@[0-9a-z]+[-\._^0-9a-z]*[0-9a-z]+[\.]{1}[a-z]{2,6}$/', strtolower($email))) {
            return true;
        } else {
            return false;
        }
    }

    function validateMobilePhoneNumberFormat($phone_number, $mask = '') {
        if ($mask != '') {
            $clear_number = preg_replace('/[^\d]/', '', $phone_number);

            if (preg_match('/^' . $mask . '$/', $clear_number)) {
                return $clear_number;
            } else {
                return FALSE;
            }
        } else {
            if ($this->getConfigValue('apps.fasteditor.enable')) {
                $clear_number = preg_replace('/[^\d]/', '', $phone_number);
                if (preg_match('/^8(\d){10}$/', $clear_number)) {
                    return $clear_number;
                } else {
                    return FALSE;
                }
            } else {
                return TRUE;
            }
        }
    }

    public static function getAttachmentsBlock() {
        global $smarty;
        return $smarty->fetch(SITEBILL_DOCUMENT_ROOT . '/apps/admin/admin/template/attachments_block.tpl');
    }

    public static function modelSimplification($model) {
        if (!empty($model)) {
            foreach ($model as $mkey => $melement) {
                foreach ($melement as $k => $v) {
                    if ($k == 'type' && ($v != 'select_by_query_multi' && $v != 'select_by_query' && $v != 'select_box' && $v != 'select_box_structure' && $v != 'structure' && $v != 'date' && $v != 'tlocation' && $v != 'client_id')) {
                        $model[$mkey]['value_string'] = $model[$mkey]['value'];
                    }
                    if (!in_array($k, array('name', 'title', 'value', 'value_string', 'type', 'image_array'))) {
                        unset($model[$mkey][$k]);
                    }
                }
            }
        }

        return $model;
    }

    public static function iconv($in_charset, $out_charset, $string) {
        if (strtolower($in_charset) == strtolower($out_charset)) {
            return $string;
        } else {
            return iconv($in_charset, $out_charset . '//IGNORE', $string);
        }
    }

    public static function removeDirectory($dir, &$msg = array()) {
        $files = scandir($dir);

        if (count($files) > 2) {
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($dir . '/' . $file)) {
                        self::removeDirectory($dir . '/' . $file, $msg);
                    } elseif (is_writable($dir . '/' . $file)) {
                        @unlink($dir . '/' . $file);
                    } else {
                        $msg[] = 'Файл/директория ' . $file . ' не удален. Удалите его самостоятельно.';
                    }
                }
            }
        }

        if (is_writable($dir)) {
            rmdir($dir);
        } else {
            $msg[] = 'Файл/директория ' . $dir . ' не удален. Удалите его самостоятельно.';
        }
    }

    function transliteMe($str) {
        $str = str_replace(array(',', '.', '/', '\\', '"', '\'', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '|', ';', '?', '<', '>', '`', '[', ']', '{', '}', '№'), '', $str);
        $str = mb_strtolower($str, SITE_ENCODING);
        $tr = array(
            "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "e", "ж" => "j",
            "з" => "z", "и" => "i", "й" => "y", "і" => "i", "ї" => "yi", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
            "ы" => "i", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", "і" => "i",
            "А" => "a", "Б" => "b",
            "В" => "v", "Г" => "g", "Д" => "d", "Е" => "e", "Ё" => "e", "Є" => "ye", "Ж" => "j",
            "З" => "z", "И" => "i", "Й" => "y", "І" => "i", "Ї" => "yi", "К" => "k", "Л" => "l",
            "М" => "m", "Н" => "n", "О" => "o", "П" => "p", "Р" => "r",
            "С" => "s", "Т" => "t", "У" => "u", "Ф" => "f", "Х" => "h",
            "Ц" => "ts", "Ч" => "ch", "Ш" => "sh", "Щ" => "sch", "Ъ" => "y",
            "Ы" => "i", "Ь" => "", "Э" => "e", "Ю" => "yu", "Я" => "ya", "І" => "i",
            " " => "-", 'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y'
        );

        $str = strtr(mb_strtolower($str, SITE_ENCODING), $tr);
        $str = preg_replace('/([^a-z0-9-_])/', '', $str);
        $str = preg_replace('/(-+)/', '-', $str);
        return $str;
    }
    public static function smarty_instance() {
        if (isset(self::$smarty_instance)) {
            return self::$smarty_instance;
        }

        self::$smarty_instance = new Smarty();
        return self::$smarty_instance;
    }

    public static function setLangSession() {
        if (isset($_GET['_lang'])) {
            $lang = trim(preg_replace('/[^a-z]/i', '', $_GET['_lang']));
            if ($lang != '') {
                $_SESSION['_lang'] = $lang;
            }
        }
        if (!isset($_SESSION['_lang']) || $_SESSION['_lang'] == '') {
            $C = SConfig::getInstance();
            if ('' == trim($C->getConfigValue('apps.language.default_lang_code'))) {
                $_SESSION['_lang'] = 'ru';
            } else {
                $_SESSION['_lang'] = trim($C->getConfigValue('apps.language.default_lang_code'));
            }
        }
        /* if(!isset($_SESSION['_lang']) || $_SESSION['_lang']==''){
          if(trim($this->getConfigValue('apps.language.default_lang_code'))==''){
          $_SESSION['_lang']='ru';
          }else{
          $_SESSION['_lang']=trim($this->getConfigValue('apps.language.default_lang_code'));
          }

          } */
    }

    public static function getClearRequestURI($test_url = '') {
        if ($test_url == '') {
            $test_url = $_SERVER['REQUEST_URI'];
        }
        $url = urldecode($test_url);
        $url = str_replace('\\', '/', $url);

        $query_str_pos = strpos($url, '?');
        if (false !== $query_str_pos) {
            //$fp=substr($url, 0, $query_str_pos);
            $REQUESTURIPATH = substr($url, 0, $query_str_pos);
        } else {
            $REQUESTURIPATH = $url;
        }
        //var_dump($fp);
        //$REQUESTURIPATH = parse_url($url, PHP_URL_PATH);





        if (preg_match('/(\/(\/+))/', $REQUESTURIPATH)) {
            return $REQUESTURIPATH;
        }
        //$REQUESTURIPATH = parse_url($url, PHP_URL_PATH);

        /* if ($REQUESTURIPATH == false) {
          $REQUESTURIPATH = urldecode($test_url);
          } */
        if ('/' === $REQUESTURIPATH) {
            return '';
        }

        //$REQUESTURIPATH=str_replace('\\', '/', $REQUESTURIPATH);
        if (substr($REQUESTURIPATH, 0, 1) === '/') {
            $REQUESTURIPATH = substr($REQUESTURIPATH, 1);
        }
        if (substr($REQUESTURIPATH, -1, 1) === '/') {
            $REQUESTURIPATH = substr($REQUESTURIPATH, 0, strlen($REQUESTURIPATH) - 1);
        }
        //var_dump($REQUESTURIPATH);
        //$REQUESTURIPATH=trim(str_replace('\\', '/', parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH)),'/');
        if (SITEBILL_MAIN_URL != '') {
            $REQUESTURIPATH = trim(preg_replace('/^' . trim(SITEBILL_MAIN_URL, '/') . '/', '', $REQUESTURIPATH), '/');
        }
        return $REQUESTURIPATH;
    }

    public function sendFirmMail($to, $from, $subject, $body, $customtpl = '') {
        require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/mailer/mailer.php');
        $mailer = new Mailer();
        //Если указано несколько почтовых ящиков для получения в $to через запятую, то делаем из него массив
        if (is_string($to)) {
            if (preg_match('/,/', $to)) {
                $to_array = explode(',', $to);
                $to = array();
                foreach ($to_array as $k => $to_email_string) {
                    array_push($to, $to_email_string);
                }
            }
        }
        $this->writeLog(__METHOD__ . ', ' . "to = " . var_export($to, true));


        global $smarty;
        $smarty->assign('letter_content', $body);
        $smarty->assign('estate_core_url', $this->getServerFullUrl());
        $tpl = SITEBILL_DOCUMENT_ROOT . '/apps/system/template/firm_mail_wrapper.tpl';
        if($customtpl != '' && file_exists($customtpl)){
            $tpl = $customtpl;
        }
        if (file_exists(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . '/firm_mail_wrapper.tpl')) {
            $tpl = SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $this->getConfigValue('theme') . '/firm_mail_wrapper.tpl';
        }
        $body = $smarty->fetch($tpl);

        if ($this->getConfigValue('use_smtp')) {
            $mailer->send_smtp($to, $from, $subject, $body, 1);
        } else {
            $mailer->send_simple($to, $from, $subject, $body, 1);
        }
    }

    /**
     * Проверка владельца записи в таблице по USER_ID, если владелец совпадает с $table_name.user_id тогда возвращаем TRUE иначе FALSE
     * @param type $table_name - название таблицы
     * @param type $user_id - идентификатор пользователя для проверки
     * @param type $control_name - тип действия (edit, delete...)
     * @param type $primary_key_name - название PRIMARY KEY в таблице
     * @param type $primary_key_value - значение PRIMARY KEY
     * @return boolean
     */
    function check_access($table_name, $user_id, $control_name, $primary_key_name, $primary_key_value) {
        if (!$user_id) {
            return true;
        }
        $DBC = DBC::getInstance();
        $enable_curator_mode = false;
        if (1 == $this->getConfigValue('enable_curator_mode')) {
            $enable_curator_mode = true;
            $has_access = 0;
            
            
            if(1 === intval($this->getConfigValue('curator_mode_fullaccess'))){
                
                $query = 'SELECT COUNT(d.id) AS _cnt FROM ' . DB_PREFIX . '_'.$table_name.' d LEFT JOIN ' . DB_PREFIX . '_user u USING(user_id) WHERE d.id=? AND u.parent_user_id=?';
                $stmt = $DBC->query($query, array($primary_key_value, $user_id));
                if ($stmt) {
                    $ar = $DBC->fetch($stmt);
                    if ($ar['_cnt'] > 0) {
                        $has_access = 1;
                    }
                }
            }else{
                $query = 'SELECT COUNT(id) AS _cnt FROM ' . DB_PREFIX . '_cowork WHERE coworker_id=? AND object_type=? AND id=?';
                $stmt = $DBC->query($query, array($user_id, $table_name, $primary_key_value));
                if ($stmt) {
                    $ar = $DBC->fetch($stmt);
                    if ($ar['_cnt'] > 0) {
                        $has_access = 1;
                    }
                }
            }
            

            /*$query = 'SELECT COUNT(id) AS _cnt FROM ' . DB_PREFIX . '_cowork WHERE coworker_id=? AND object_type=? AND id=?';
            $stmt = $DBC->query($query, array($user_id, $table_name, $primary_key_value));
            if ($stmt) {
                $ar = $DBC->fetch($stmt);
                if ($ar['_cnt'] > 0) {
                    $has_access = 1;
                }
            }*/
        }


        $where = array();
        $where_val = array();

        $where[] = '`' . $primary_key_name . '`=?';
        $where_val[] = $primary_key_value;


        if ($enable_curator_mode) {
            $where[] = '(`user_id`=? OR (`user_id`!=? AND 1=' . $has_access . '))';
            $where_val[] = $user_id;
            $where_val[] = $user_id;
        } else {
            $where[] = '`user_id`=?';
            $where_val[] = $user_id;
        }


        $query = 'SELECT `' . $primary_key_name . '` FROM `' . DB_PREFIX . '_' . $table_name . '` WHERE ' . implode(' AND ', $where);
        $stmt = $DBC->query($query, $where_val);
        if (!$stmt) {
            return false;
        }
        $ar = $DBC->fetch($stmt);
        if ($ar[$primary_key_name] > 0) {
            return true;
        }
        return false;
    }

    function need_check_access($table_name) {
        return $_SESSION['politics'][$table_name]['check_access'];
    }

    function get_check_access_user_id($table_name) {
        return $_SESSION['politics'][$table_name]['user_id'];
    }

    /**
     * Перенаправляем неавторизованного пользователя на форму авторизации
     */
    function go_to_login() {
        header('location: ' . SITEBILL_MAIN_URL . '/login/');
        exit();
    }

    /**
     * Ищем в таблице emailtemplates шаблон с именем $name
     * Если находим, то делаем smarty fetch для subject и message 
     * Предварительно все переменные должны быть assign-нуты в smarty
     * @param type $name - системное название шаблона
     * @return mixed (массив с готовый с subject и message, если шаблон найдет. false - если шаблон не найден)
     */
    function fetch_email_template($name) {
        global $smarty;
        $ra = array();
        if ($this->getConfigValue('apps.emailtemplates.enable')) {
            require_once (SITEBILL_DOCUMENT_ROOT . '/apps/emailtemplates/admin/admin.php');
            $emailtemplates_admin = new emailtemplates_admin();
            return $emailtemplates_admin->compile_template($name);
        }
        return false;
    }

    function clear_apps_cache() {
        //Очищаем кэш apps
        $DBC = DBC::getInstance();
        $query = "TRUNCATE TABLE " . DB_PREFIX . "_apps";
        $stmt = $DBC->query($query, array(), $rows, $success);
    }

    public function yandex_translate($value, $language){
        if ($language == 'ge') {
            $language = 'ka';
        }
        $api_key = $this->getConfigValue('apps.language.yandex_translate_api_key');
        if ($api_key == '') {
            return '';
        }
        if ($value == '') {
            return '';
        }
        
        $url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=' . $api_key . '&format=html&lang=' . $language . '&text=' . urlencode($value);
		        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $output = curl_exec($ch);
        curl_close($ch);
        
		if(false===$result){
			return '';
		}
		$res=json_decode($result);
		if($res->code=='200'){
			return $res->text[0];
		}elseif($res->code=='403'){
			$err='Превышено суточное ограничение на количество запросов';
		}elseif($res->code=='404'){
			//resetCurrentYandexKey();
			$err='Превышено суточное ограничение на объем переведенного текста';
		}elseif($res->code=='413'){
			$err='Превышен максимально допустимый размер текста';
		}elseif($res->code=='422'){
			$err='Текст не может быть переведен';
		}elseif($res->code=='402'){
			//resetCurrentYandexKey();
			$err='Ключ API заблокирован';
		}else{
            $err='Другая ошибка';
        }
        $this->writeLog(__METHOD__ . ', value = ' . $value . ', target_language = ' . $language . ', error = ' . $err);
        return '';
    }

    public function google_translate($value, $language) {
        if ($language == 'ge') {
            $language = 'ka';
        }
        $api_key = $this->getConfigValue('apps.language.google_translate_api_key');
        if ($api_key == '') {
            return $value;
        }
        if ($value == '') {
            return '';
        }
        //echo 'google translate';
        //%D0%BF%D1%80%D0%B8%D0%B2%D0%B5%D1%82&target=en&key={YOUR_API_KEY}
        $url = 'https://translation.googleapis.com/language/translate/v2?key=' . $api_key . '&format=html&target=' . $language . '&q=' . urlencode($value);
        //echo $url.'<br>';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $langdata = json_decode($output, true);
        $this->writeLog(__METHOD__ . ', value = ' . $value . ', target_language = ' . $language . ', langdata = ' . var_export($langdata, true));

        //return $geodata;
        //print_r($langdata);

        if ($langdata['data']['translations'][0]['translatedText'] != '') {
            $this->writeLog(__METHOD__ . ', value = ' . $value . ', target_language = ' . $language . ', translatedText = ' . $langdata['data']['translations'][0]['translatedText']);
            return $langdata['data']['translations'][0]['translatedText'];
        }
        return '';
    }

    public function mtphn($s) {
        if (!function_exists('transliterator_transliterate') or ! function_exists('metaphone')) {
            echo 'Для работы функции метафона нужно установить (PHP 5 >= 5.4.0, PHP 7, PECL intl >= 2.0.0';
            exit;
        }
        $key = transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", $s);
        $key = preg_replace('/[-\s]+/', '-', $key);
        $key = str_replace('ʼ', '', $key);
        //echo $key.'<br>';
        return metaphone($key);
    }

    public static function get_microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }

    function load_topic_links() {
        if ($this->loaded_links) {
            return $this->ral;
        }
        //echo '<hr>load<br>';
        //echo 'Загрузка правил перелинковки из таблицы topic_links<br>';
        $DBC = DBC::getInstance();
        $this->ral = array();
        $query = 'SELECT * FROM ' . DB_PREFIX . '_topic_links';
        $stmt = $DBC->query($query, array(), $success);
        if ($DBC->getLastError()) {
            //echo '<font color="red">' . $DBC->getLastError() . '</font><br>';
        }

        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                $this->ral[$ar['topic_id']]['link_topic_id'] = $ar['link_topic_id'];
                //echo $ar['params'].'<br>';
                //print_r(json_decode($ar['params']));
                //echo '<br>';
                $json_params_decode = json_decode($ar['params']);
                if (is_object($json_params_decode)) {
                    $this->ral[$ar['topic_id']]['params'] = $json_params_decode;
                } elseif ($ar['params'] != '') {
                    //echo $ar['params'].'<br>';
                    $this->ral[$ar['topic_id']]['params'] = $ar['params'];
                }
            }
        }
        //echo 'Загрузка правил перелинковки завершена<br>';
        $this->loaded_links = true;
        return $this->ral;
    }

    function reachEventStat($events) {
        if (file_exists(SITEBILL_DOCUMENT_ROOT . '/apps/statoid/admin/admin.php') && 1 == $this->getConfigValue('apps.statoid.enable')) {
            require_once SITEBILL_DOCUMENT_ROOT . '/apps/statoid/admin/admin.php';
            $S = new statoid_admin();
            foreach ($events as $event) {
                $S->collectEvent($event['event'], $event['id']);
            }
        }
    }

    function reachTargetStat($targets) {
        if (file_exists(SITEBILL_DOCUMENT_ROOT . '/apps/statoid/admin/admin.php') && 1 == $this->getConfigValue('apps.statoid.enable')) {
            require_once SITEBILL_DOCUMENT_ROOT . '/apps/statoid/admin/admin.php';
            $S = new statoid_admin();
            foreach ($targets as $target) {
                $S->collectTarget($target['event'], $event['id']);
            }
        }
    }

}

/*class Url_Helper {
	private static $endSlashes=false;
	public static function getUrl($url){
		return $_SERVER['HTTP_HOST'].'/'.$url.(self::$endSlashes ? '/' : '');
	}
	public static function setEndSlashes($b){
		self::$endSlashes=(bool)$b;
	}
}*/
