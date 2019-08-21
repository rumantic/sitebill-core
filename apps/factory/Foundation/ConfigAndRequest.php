<?php


namespace factory\Foundation;


use system\lib\system\SConfig;

class ConfigAndRequest {
    /**
     * @var SConfig
     */
    private $SConf;

    function __construct() {
        $this->SConf = SConfig::getInstance();

    }

    function getConfigValue ($key) {
        return $this->SConf->getConfigValue($key);
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
    function escape($text) {
        if (get_magic_quotes_gpc()) {
            $text = stripcslashes($text);
        }
        return $text;
    }

    private function getSafeValue($value) {
        return preg_replace('/(\/\*[^\/]*\*\/)/', '', $value);
    }

}
