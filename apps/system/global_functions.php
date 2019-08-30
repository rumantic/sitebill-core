<?php
use system\lib\system\SConfig;
use system\lib\system\multilanguage\Multilanguage;
namespace {
    /**
     * Обычная процедурная функция подключается в шаблоне и выполняет перевод с помощью google_translate в шаблонах
     * Создает для каждой переводимой строки транслит ключ и записывает в /template/frontend/шаблон/language/ЯЗЫК/dictionary.ini нужный перевод для этого ключа
     * В шаблоне вместо текстовой статичной строки Привет мир! Писать так {_e t="Привет мир!"}
     * @param array $t array('t' => 'value')
     * @return string
     */
    function _translate($t) {
        /*
        if (function_exists('transliterator_transliterate')) {
            $key = transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", $t['t']);
            $key = preg_replace('/[-\s]+/', '-', $key);
            $key = str_replace('ʼ', '', $key);
        } else {
            $key = $sitebill->transliteMe($t['t']);
        }
         *
         */
        $key = md5($t['t']);

        $template_key = SConfig::getConfigValue('theme') . '_template';
        /*
        if (strlen($key) > 100) {
            $key = substr($key, 0, 100) . '_' . substr(md5($key), 0, 7);
        }
         *
         */
        Multilanguage::appendTemplateDictionary(SConfig::getConfigValue('theme'));
        if (!Multilanguage::is_set_any($key, $template_key)) {
            //echo 'from db '.$key;
            $lang = Multilanguage::get_current_language();
            //$translate = $sitebill->google_translate($t['t'], $lang);
            if ($translate != '') {
                //require_once (SITEBILL_DOCUMENT_ROOT . '/apps/language/admin/admin.php');
                //require_once (SITEBILL_DOCUMENT_ROOT . '/apps/language/admin/admin_template.php');
                //$language_admin_template = new language_admin_template();
                //$template_languages = $language_admin_template->getTemplateWordsArray($sitebill->getConfigValue('theme'), $lang);
                $terms = $template_languages['keys'];
                $values = $template_languages['words'];
                @array_push($terms, $key);
                $values[$key][$lang] = $translate;
                //array_push($values, $translate);
                //$terms[0] = $key;
                //$values[0][$lang] = $translate;
                //$language_admin_template->saveTemplateWords($sitebill->getConfigValue('theme'), $terms, $values);
                Multilanguage::insert_lang_words(SConfig::getConfigValue('theme') . '_template', $lang, $key, $translate);
                return $translate;
            } else {
                Multilanguage::insert_lang_words(SConfig::getConfigValue('theme') . '_template', $lang, $key, $t['t']);
            }
            return $t['t'];
        } else {
            return Multilanguage::_any($key, $template_key);
        }
    }

    function _e($value) {
        return _translate(array('t' => $value));
    }
}
