<?php
namespace system\lib\system\multilanguage;

use system\lib\system\SConfig;
use system\lib\system\DBC;


class Multilanguage {

    private static $instance = NULL;
    private static $default_lang = 'ru';
    private static $default_mode = 'backend';
    private static $current_lang = '';
    private static $current_mode = '';
    private $language = 'ru';
    private $mode = 'frontend';
    private static $words = array();
    private static $apps_words = array();
    private static $backend_words = array();
    private static $frontend_words = array();
    private static $is_tpl_loaded = false;

    public static function start($mode = '', $lang_code = '') {
        self::setOptions($mode, $lang_code);
    }

    public static function getInstance($mode = '', $lang_code = '') {
        if (self::$instance == NULL) {
            self::$instance = new Multilanguage();
            self::$instance->setOpt($mode, $lang_code);
        } else {
            self::$instance->setOpt($mode, $lang_code);
        }
        return self::$instance;
    }

    public static function is_set_any($key, $app) {
        if (isset(self::$apps_words['empty'][$key])) {
            return true;
        } elseif (isset(self::$words[$key])) {
            return true;
        }

        if ($app != '' && isset(self::$apps_words[$app])) {
            if (isset(self::$apps_words[$app][$key])) {
                return true;
            }
        }
        return false;
    }

    public static function is_set($key, $app = '') {
        if ($app != '' && isset(self::$apps_words[$app])) {
            if (isset(self::$apps_words[$app][$key])) {
                return true;
            } else {
                return false;
            }
        } else {
            if (isset(self::$apps_words['empty'][$key])) {
                return true;
            } elseif (isset(self::$words[$key])) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    public static function _any($key, $app = '') {
        //echo 'key = '.$key.', app = '.$app.'<br>';
            if (isset(self::$words[$key])) {
                return self::$words[$key];
            } elseif (isset(self::$apps_words['empty'][$key])) {
                return self::$apps_words['empty'][$key];
            }
        
        if ($app != '' && isset(self::$apps_words[$app])) {
            if (isset(self::$apps_words[$app][$key])) {
                return self::$apps_words[$app][$key];
            } else {
                return $app . '.' . $key;
            }
        }       
        return $key;
    }
    

    public static function _($key, $app = '') {
        //echo 'key = '.$key.', app = '.$app.'<br>';
        if ($app != '' && isset(self::$apps_words[$app])) {
            if (isset(self::$apps_words[$app][$key])) {
                return self::$apps_words[$app][$key];
            } else {
                return $app . '.' . $key;
            }
        } else {
            if (isset(self::$words[$key])) {
                return self::$words[$key];
            } else {
                return $key;
            }
        }
    }

    public static function text($key) {
        if (isset(self::$words[$key])) {
            return self::$words[$key];
        } else {
            return $key;
        }
    }

    public static function appendAppDictionary($app_name, $template = '', $force = false) {
        //return;
        if (isset(self::$apps_words[$app_name])) {
            return;
        } elseif (self::$current_lang == '') {
            return;
        }
        //if ( !$force ) {
        //    return;
        //}
        //echo $app_name.'='.self::$current_lang.'<br>';
        //echo 'app '.$app_name.'<br>';

        $file_name = SITEBILL_DOCUMENT_ROOT . '/apps/' . $app_name . '/language/' . self::$current_lang . '/dictionary.ini';

        if (file_exists($file_name)) {
            self::$apps_words[$app_name] = parse_ini_file($file_name, true);
            //echo 'init a 1 '.$file_name.'<br>';
            self::init_db_lang_words(self::$apps_words);
        } else {
            $file_name = SITEBILL_DOCUMENT_ROOT . '/apps/' . $app_name . '/language/' . self::$default_lang . '/dictionary.ini';
            if (file_exists($file_name)) {
                //echo $file_name . '<br>';
                self::$apps_words[$app_name] = parse_ini_file($file_name, true);
                self::init_db_lang_words(self::$apps_words);
            }
        }

        $SConfig = SConfig::getInstance();
        $template = $SConfig->getConfigValue('theme');

        if ($template != '' && file_exists(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $template . '/apps/' . $app_name . '/language/' . self::$current_lang . '/dictionary.ini')) {
            $words = self::$apps_words[$app_name];

            $new_words = parse_ini_file(SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $template . '/apps/' . $app_name . '/language/' . self::$current_lang . '/dictionary.ini', true);
            if (isset(self::$apps_words[$app_name])) {
                self::$apps_words[$app_name] = array_merge(self::$apps_words[$app_name], $new_words);
            } else {
                self::$apps_words[$app_name] = $new_words;
            }
            self::init_db_lang_words(self::$apps_words);
        }

        //self::assign($smarty);
    }

    public static function init_db_lang_words($words) {
        //return;
        //echo '<pre>';
        //print_r($words);
        //echo '</pre>';
        //echo 'init_db_lang_words<br>';
        $DBC = DBC::getInstance();
        $query = 'INSERT INTO ' . DB_PREFIX . '_lang_words (word_app, lang_key, word_key, word_default, word_pack) values (?, ?, ?, ?, ?)';
        foreach ($words as $app => $app_array) {
            if (!is_array($app_array)) {
                $app = 'empty';
                foreach ($words as $key => $value) {
                    $stmt = $DBC->query($query, array($app, self::$current_lang, $key, $value, substr($value, 0, 50)), $success);
                    if (!$success) {
                        //echo $DBC->getLastError() . '<br>';
                    } else {
                        //echo 'app = '.$app.', key = '.$key.', value = '.$value.', lang_key = '.self::$current_lang.'<br>';
                    }
                }
                return true;
            } else {
                foreach ($app_array as $key => $value) {
                    $stmt = $DBC->query($query, array($app, self::$current_lang, $key, $value, substr($value, 0, 50)), $success);
                    if (!$success) {
                        //echo $DBC->getLastError() . '<br>';
                    } else {
                        //echo 'app = '.$app.', key = '.$key.', value = '.$value.', lang_key = '.self::$current_lang.'<br>';
                    }
                }
            }
        }
        return true;
    }

    public static function insert_lang_words($app, $lang, $key, $value) {
        $DBC = DBC::getInstance();
        $query = 'INSERT INTO ' . DB_PREFIX . '_lang_words (word_app, lang_key, word_key, word_default, word_pack) values (?, ?, ?, ?, ?)';
        $stmt = $DBC->query($query, array($app, $lang, $key, $value, substr($value, 0, 50)), $success);
    }

    public static function load_db_lang_words() {
        //return;
        //global $smarty;

        $SConfig = SConfig::getInstance();
        $template_key = $SConfig->getConfigValue('theme') . '_template';

        $DBC = DBC::getInstance();
        $query = 'SELECT * FROM ' . DB_PREFIX . '_lang_words WHERE lang_key=?';
        //echo self::$current_lang;
        $stmt = $DBC->query($query, array(self::$current_lang));
        if ($stmt) {
            while ($ar = $DBC->fetch($stmt)) {
                //echo $ar['word_key'].' = '.$ar['word_default'].'<br>';
                self::$apps_words[$ar['word_app']][$ar['word_key']] = $ar['word_default'];
                if ($ar['word_app'] == 'empty') {
                    self::$words[$ar['word_key']] = $ar['word_default'];
                }
                if ($ar['word_app'] == $template_key) {
                    //$smarty->assign($ar['word_key'], $ar['word_default']);
                    self::$is_tpl_loaded = true;
                }
            }
        }
    }

    public static function assign(&$smarty) {
        if (!is_object($smarty)) {
            return false;
        }
        foreach (self::$words as $k => $w) {
            $smarty->assign($k, $w);
        }

        $smarty->assign('apps_words', self::$apps_words);
    }

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    private static function setOptions($mode, $lang_code) {
        $lang_code = trim(preg_replace('/[^a-z]/i', '', $lang_code));
        if ($mode != '' AND in_array($mode, array('frontend', 'backend'))) {
            self::$current_mode = $mode;
        } else {
            self::$current_mode = (self::$current_mode == '' ? self::$default_mode : self::$current_mode);
        }
        if ($lang_code != '') {
            self::$current_lang = $lang_code;
        } else {
            self::$current_lang = (self::$current_lang == '' ? self::$default_lang : self::$current_lang);
        }
        self::load_db_lang_words();
        self::loadWords();
        //self::init_db_lang_words(self::$words);
        global $smarty;
        self::assign($smarty);
    }

    private function setOpt($mode, $lang_code) {
        if ($mode != '' AND in_array($mode, array('frontend', 'backend'))) {
            self::$current_mode = $mode;
        } else {
            self::$current_mode = (self::$current_mode == '' ? self::$default_mode : self::$current_mode);
        }
        if ($lang_code != '') {
            self::$current_lang = $lang_code;
        } else {
            self::$current_lang = (self::$current_lang == '' ? self::$default_lang : self::$current_lang);
        }
        self::loadWords();
        global $smarty;
        self::assign($smarty);
    }

    private static function loadWords() {
        $dictionary = array();
        if (empty(self::$words)) {
            self::loadBackendWords();
            self::loadFrontendWords();
            self::$words = array_merge(self::$words, self::$backend_words);
            self::$words = array_merge(self::$words, self::$frontend_words);
            //echo '<pre>';
            //print_r(self::$words);
            //echo '</pre>';
            //self::init_db_lang_words(self::$words);
        }
        /*
          $file_name=SITEBILL_DOCUMENT_ROOT.'/apps/language/language/'.self::$current_lang.'/'.self::$current_mode.'.ini';
          if(file_exists($file_name)){

          }else{
          $file_name=SITEBILL_DOCUMENT_ROOT.'/apps/language/language/'.self::$default_lang.'/'.self::$default_mode.'.ini';
          }
          self::$words=parse_ini_file($file_name,true); */

        /* if ( self::$current_mode == 'frontend' ) {
          self::loadBackendWords();
          self::$words = array_merge(self::$words, self::$backend_words);
          } */
    }

    public static function appendTemplateDictionary($template_name) {
        if (self::$is_tpl_loaded) {
            return;
        }
        global $smarty;
        $file_name = SITEBILL_DOCUMENT_ROOT . '/template/frontend/' . $template_name . '/language/' . self::$current_lang . '/dictionary.ini';
        //echo $file_name.'<br>';

        if (file_exists($file_name)) {
            //echo $file_name . '<br>';
            $words = parse_ini_file($file_name, true);
            if (!is_object($smarty)) {
                return false;
            }
            foreach ($words as $k => $w) {
                self::$apps_words[$template_name . '_template'][$k] = $w;
                self::$words[$k] = $w;
                $smarty->assign($k, $w);
            }
            //echo 'init t<br>';

            self::init_db_lang_words(self::$apps_words);
            self::$is_tpl_loaded = true;
        }
    }

    private static function loadBackendWords() {
        $file_name = SITEBILL_DOCUMENT_ROOT . '/apps/language/language/' . self::$current_lang . '/backend.ini';
        if (file_exists($file_name)) {
            
        } else {
            $file_name = SITEBILL_DOCUMENT_ROOT . '/apps/language/language/' . self::$default_lang . '/backend.ini';
        }
        //echo $file_name . '<br>';
        self::$backend_words = parse_ini_file($file_name, true);
        //echo 'init b<br>';

        self::init_db_lang_words(self::$backend_words);
    }

    private static function loadFrontendWords() {
        $file_name = SITEBILL_DOCUMENT_ROOT . '/apps/language/language/' . self::$current_lang . '/frontend.ini';
        if (file_exists($file_name)) {
            
        } else {
            $file_name = SITEBILL_DOCUMENT_ROOT . '/apps/language/language/' . self::$default_lang . '/frontend.ini';
        }
        //echo $file_name . '<br>';

        self::$frontend_words = parse_ini_file($file_name, true);
        //echo 'init f<br>';
        self::init_db_lang_words(self::$frontend_words);
    }

    public static function availableLanguages() {
        $langs = array();

        require_once SITEBILL_DOCUMENT_ROOT . '/apps/language/admin/admin.php';
        $LM = new language_admin();

        $_langs = $LM->getLanguages();
        if (count($_langs) > 0) {
            foreach ($_langs as $lk => $lv) {
                $langs[$lk] = $lk;
            }
        }
        return $langs;
        /* $path=SITEBILL_DOCUMENT_ROOT.'/apps/system/language/';
          $skip = array('.', '..', '.svn');
          $files = scandir($path);
          foreach($files as $file) {
          if(!in_array($file, $skip)){
          $langs[$file]=$file;
          }
          }
          return $langs; */
    }

    public static function get_current_language() {
        return $_SESSION['_lang'];
    }

    public static function foreignLanguages() {
        $languages = self::availableLanguages();
        unset($languages['ru']);
        return $languages;
    }

    public static function set_word($key, $value, $app = '') {
        if ($app != '' && isset(self::$apps_words[$app])) {
            self::$apps_words[$app][$key] = $value;
        } else {
            self::$words[$key] = $value;
        }
    }

}
