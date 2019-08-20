<?php
namespace api\classes;
use system\lib\system\DBC;
use system\lib\system\permission\Permission;

defined('SITEBILL_DOCUMENT_ROOT') or die('Restricted access');

/**
 * Model REST class
 * @author Kondin Dmitriy <kondin@etown.ru> http://www.sitebill.ru
 */
class API_model extends API_Common {

    private $record_id;

    public function _get_models() {
        $DBC = DBC::getInstance();
        $query = "SELECT * FROM " . DB_PREFIX . "_table ORDER BY name ASC, table_id DESC";
        $stmt = $DBC->query($query);
        if (!$stmt) {
            return $this->request_failed('models list not defined');
        }
        while ($ar = $DBC->fetch($stmt)) {
            $models[] = array('id' => $ar['table_id'], 'name' => $ar['name']);
        }
        return $this->json_string($models);
    }

    public function _load_data() {
        $model_name = $this->request->get('model_name');
        $primary_key = $this->request->get('primary_key');
        $key_value = $this->request->get('key_value');
        $model_object = $this->init_custom_model_object($model_name);
        $user_id = $this->get_my_user_id();

        $permission = new Permission();
        if (!$permission->get_access($user_id, $model_name, 'access')) {
            $response = new API_Response('error', _e('Доступ запрещен'));
            return $this->json_string($response->get());
        }


        if ($model_object) {
            $data_array = $model_object->load_by_id($key_value);
            $tabs = $this->extract_tabs($data_array);

            $ret = array(
                'state' => 'success',
                $primary_key => $key_value,
                'data' => $data_array,
                'tabs' => $tabs
            );
            return $this->json_string($ret);
        }
        return $this->request_failed('model not defined');
    }

    public function extract_tabs($data_array) {
        $tabs = array();
        if (is_array($data_array)) {
            foreach ($data_array as $key => $item_array) {
                if ($item_array['tab'] != '') {
                    $tabs[$item_array['tab']][] = $key;
                } else {
                    $tabs[$this->getConfigValue('default_tab_name')][] = $key;
                }
            }
        }
        if (count($tabs) > 0) {
            return $tabs;
        }
        return false;
    }

    public function _load_grid_columns() {
        $model_name = $this->request->get('model_name');
        $user_id = $this->get_my_user_id();
        $columns = $this->get_columns($model_name, $user_id);
        if ($columns) {
            $response = new API_Response('success', 'load complete', $columns);
        } else {
            $response = new API_Response('error', 'columns list empty');
        }
        return $this->json_string($response->get());
    }

    public function get_columns($model_name, $user_id) {
        $DBC = DBC::getInstance();
        $used_fields = array();
        $query = 'SELECT * FROM ' . DB_PREFIX . '_table_grids WHERE `action_code`=?';
        $action_code = $this->get_grid_action_code($model_name, $user_id);
        $stmt = $DBC->query($query, array($action_code));
        if ($stmt) {
            $ar = $DBC->fetch($stmt);
            $used_fields = json_decode($ar['grid_fields']);
            $meta_fields = (array) json_decode($ar['meta']);
            if (count($used_fields) > 0) {
                $ra['grid_fields'] = $used_fields;
                $ra['meta'] = $meta_fields;
                return $ra;
            }
        }
        return false;
    }

    public function _uppend_uploads() {
        $model_object = $this->init_custom_model_object($this->request->get('model_name'));
        //$this->writeLog($this->request->get('image_field'));
        //$this->writeArrayLog($model_object->data_model);
        //$table, $field, $pk_field, $record_id, $name_template = ''
        if ($this->request->get('model_name') == 'user') {
            $images = $model_object->appendUploadsUser($this->request->get('model_name'), $this->request->get('primary_key'), $this->request->get('key_value'));
        } else {
            $images = $model_object->appendUploads($this->request->get('model_name'), $model_object->data_model[$this->request->get('model_name')][$this->request->get('image_field')], $this->request->get('primary_key'), $this->request->get('key_value'));
        }

        if ($images) {
            $ret = array(
                'state' => 'success',
                'data' => $images
            );
            return $this->json_string($ret);
        }
        return $this->request_failed('uppend_uploads failed');
    }

    public function _delete() {
        $model_name = $this->request->get('model_name');
        $primary_key = $this->request->get('primary_key');
        $key_value = $this->request->get('key_value');
        $user_id = $this->get_my_user_id();

        $permission = new Permission();
        //внутри get_access еще надо реализовать проверку доступа к записям из data 
        //сейчас проверка опционально проверяет только группу и разрешает админам удалять

        if ($permission->get_access($user_id, $model_name, 'access')) {


            $model_object = $this->init_custom_model_object($model_name);
            if ($model_object->delete_data($model_name, $primary_key, $key_value)) {
                $response = new API_Response('success', 'delete complete');
            } else {
                $response = new API_Response('error', $model_object->GetErrorMessage());
            }
        } else {
            $response = new API_Response('error', _e('Доступ запрещен'));
        }
        return $this->json_string($response->get());
    }

    public function _delete_data() {
        $user_id = $this->get_my_user_id();

        $data_id = $this->request->get('id');

        $permission = new Permission();
        //внутри get_access еще надо реализовать проверку доступа к записям из data 
        //сейчас проверка опционально проверяет только группу и разрешает админам удалять
        if ($permission->get_access($user_id, 'data', 'access')) {
            require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/data/data_manager.php');
            $data_manager = new Data_Manager();
            if ($data_manager->delete_data('data', 'id', $data_id)) {
                $response = new API_Response('success', 'delete complete');
            } else {
                $response = new API_Response('error', $data_manager->GetErrorMessage());
            }
        } else {
            $response = new API_Response('error', 'error on delete');
        }
        return $this->json_string($response->get());
    }

    public function _unpublish_data() {
        $user_id = $this->get_my_user_id();

        $data_id = $this->request->get('id');

        $permission = new Permission();
        //внутри get_access еще надо реализовать проверку доступа к записям из data 
        //сейчас проверка опционально проверяет только группу и разрешает админам удалять
        if ($permission->get_access($user_id, 'data', 'access')) {
            require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/data/data_manager.php');
            $data_manager = new Data_Manager();
            $data_array = $data_manager->load_by_id($data_id);
            $data_array['active']['value'] = 0;
            $data_manager->edit_data($data_array, 0, $data_id);
            if (!$data_manager->getError()) {
                $response = new API_Response('success', 'unpublish complete');
            } else {
                $response = new API_Response('error', $data_manager->GetErrorMessage());
            }
        } else {
            $response = new API_Response('error', 'error on unpublish');
        }
        return $this->json_string($response->get());
    }

    public function _get_model() {
        $model_id = $this->getRequestValue('id');
        //$this->writeLog(__METHOD__.', id = '.$model_id);

        $DBC = DBC::getInstance();
        $query = "SELECT name FROM " . DB_PREFIX . "_table WHERE table_id=?";
        $stmt = $DBC->query($query, array($model_id));
        if (!$stmt) {
            return $this->request_failed('model not defined');
        }
        $ar = $DBC->fetch($stmt);
        $model_name = $ar['name'];
        if ($model_name != '') {

            require_once (SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/object_manager.php');
            require_once (SITEBILL_DOCUMENT_ROOT . '/apps/customentity/admin/admin.php');
            $customentity_admin = new customentity_admin();
            $customentity_admin->custom_construct($model_name);
            $ret = array(
                'id' => $model_id,
                'name' => $model_name,
                'columns' => array_values($customentity_admin->data_model[$model_name])
            );
            //$this->writeLog(__METHOD__ . ', name = ' . $model_name);
            //$this->writeLog(__METHOD__ . ', model = <pre>' . var_export($customentity_admin->data_model, true) . '</pre>');
            return $this->json_string($ret);
        }
        return $this->request_failed('model not defined');
    }

    public function _select() {
        //$data_id = $this->getRequestValue('id');
        $data = json_decode(file_get_contents('php://input'), true);

        require_once (SITEBILL_DOCUMENT_ROOT . '/apps/apiproxy/admin/crm_object.php');
        $crm_object = new crm_object();
        $crm_object->save_selected($this->get_placement(), $this->get_crm_item_id(), $this->get_site(), $this->get_site_user_id(), $data['selected_items']);

        $ret = array('data_id' => $data['selected_items']);
        return $this->json_string($ret);
    }

    public function _load_selected() {
        //$data_id = $this->getRequestValue('id');
        $data = json_decode(file_get_contents('php://input'), true);

        require_once (SITEBILL_DOCUMENT_ROOT . '/apps/apiproxy/admin/crm_object.php');
        $crm_object = new crm_object();
        $selected = $crm_object->load_selected($this->get_placement(), $this->get_crm_item_id(), $this->get_site(), $this->get_site_user_id());

        $ret = array('selected' => $selected);
        return $this->json_string($ret);
    }

    public function _load_dictionary() {
        $columnName = $this->request->get('columnName');
        $model_name = $this->request->get('model_name');
        $switch_off_ai_mode = $this->request->get('switch_off_ai_mode');

        if ($model_name == '') {
            $model_name = 'data';
        }


        require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/components/model_tags/model_tags.php');
        $model_tags = new model_tags();
        //$this->writeLog($switch_off_ai_mode);
        //$this->writeLog(boolval($switch_off_ai_mode));
        if (boolval($switch_off_ai_mode)) {
            //$this->writeLog('switch off ai mode');
            $model_tags->set_ai_mode(false);
        } else {
            //$this->writeLog('switch on ai mode');
            $model_tags->set_ai_mode(true);
        }

        $model_tags->enable_primary_key_mode();

        $model_object = $this->init_custom_model_object($model_name);

        $dictionary_array = $model_tags->get_array($model_name, $columnName, 'array', $model_object->data_model[$model_name]);
        //$this->writeArrayLog($dictionary_array);

        if ($model_tags->getError()) {
            $response = new API_Response('error', $model_tags->GetErrorMessage());
            return $this->json_string($response->get());
        } else {
            $ret = array('data' => $dictionary_array);
        }
        return $this->json_string($ret);
    }

    public function _load_ads_by_term() {
        $term = $this->request->get('term');
        require_once(SITEBILL_DOCUMENT_ROOT . '/apps/search/admin/admin.php');
        $search_admin = new search_admin();
        $search_result = $search_admin->get_terms($term, true);
        if ($search_admin->getError()) {
            $response = new API_Response('error', $search_admin->GetErrorMessage());
            return $this->json_string($response->get());
        } else {
            $ret = $search_result;
            //$ret = array('data' => $this->fake_adv());
        }
        return $this->json_string($ret);
    }

    function fake_adv() {
        $ra = array();
        for ($i = 0; $i < 5; $i++) {
            $ra[] = array('adv' => 'login' . $i);
        }
        return $ra;
    }

    public function update_native_request_params($ql_items) {
        foreach ($ql_items as $key => $value) {
            $_REQUEST[$key] = $value;
            $this->setRequestValue($key, $value);
        }
    }

    public function _native_update() {
        $model_name = $this->request->get('model_name');
        $key_value = $this->request->get('key_value');
        $ql_items = $this->request->get('ql_items');
        $user_id = $this->get_my_user_id();
        //$this->writeArrayLog($ql_items);

        require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/model/model.php');
        $data_model = new Data_Model();

        $permission = new Permission();
        if (!$permission->get_access($user_id, $model_name, 'access')) {
            $response = new API_Response('error', _e('Доступ запрещен'));
            return $this->json_string($response->get());
        }

        if (count($ql_items) > 0) {
            $this->update_native_request_params($ql_items);
        }

        $model_object = $this->init_custom_model_object($model_name);
        $primary_key = $model_object->primary_key;
        $this->setRequestValue($primary_key, $key_value);
        $this->setRequestValue('do', 'edit_done');
        $model_object->rest_edit_done();
        if ($model_object->getError()) {
            $response = new API_Response('error', $model_object->GetErrorMessage());
        } else {
            $response = new API_Response('success', 'edit native complete');
        }
        return $this->json_string($response->get());
    }

    public function _native_insert() {
        $model_name = $this->request->get('model_name');
        $key_value = $this->request->get('key_value');
        $ql_items = $this->request->get('ql_items');
        $user_id = $this->get_my_user_id();

        require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/model/model.php');
        $data_model = new Data_Model();

        $permission = new Permission();
        if (!$permission->get_access($user_id, $model_name, 'access')) {
            $response = new API_Response('error', _e('Доступ запрещен'));
            return $this->json_string($response->get());
        }

        if (count($ql_items) > 0) {
            $this->update_native_request_params($ql_items);
        }

        $model_object = $this->init_custom_model_object($model_name);
        $primary_key = $model_object->primary_key;
        $this->setRequestValue('do', 'new_done');
        $model_object->rest_new_done();
        if ($model_object->getError()) {
            $response = new API_Response('error', $model_object->GetErrorMessage());
        } else {
            $new_record_id = $model_object->get_new_record_id();
            $response = new API_Response('success', 'new native complete', array('new_record_id' => $new_record_id));
        }
        return $this->json_string($response->get());
    }

    /**
     * Универсальный метод для редактирования любой сущности. В аргументах передаем массив редактируемых значений
     */
    public function _graphql_update() {
        $model_name = $this->request->get('model_name');
        $key_value = $this->request->get('key_value');
        $ql_items = $this->request->get('ql_items');
        $only_ql = $this->request->get('only_ql');
        $user_id = $this->get_my_user_id();

        require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/model/model.php');
        $data_model = new Data_Model();

        $permission = new Permission();
        if (!$permission->get_access($user_id, $model_name, 'access')) {
            $response = new API_Response('error', _e('Доступ запрещен'));
            return $this->json_string($response->get());
        }



        //$this->writeLog('key_value = '.$key_value);
        //$this->writeArrayLog($ql_items);

        if (count($ql_items) > 0) {
            $this->update_native_request_params($ql_items);
        }


        $model_object = $this->init_custom_model_object($model_name);
        $primary_key = $model_object->primary_key;
        $this->setRequestValue($primary_key, $key_value);

        if ((int) $key_value == 0) {
            //Предварительно проверим данные, чтобы не создавать заведомо неправильную запись
            $model_data = $model_object->data_model[$model_name];
            $model_data = $data_model->init_model_data_from_request($model_data);
            if (!$model_object->check_data($model_data)) {
                $response = new API_Response('error', $model_object->GetErrorMessage());
                return $this->json_string($response->get());
            }

            //$this->writeArrayLog($model_data['price']);
            //$this->writeLog('key value 0 = '.$key_value);


            $this->_new_empty_record();
            if ($this->getError()) {
                $response = new API_Response('error', $this->GetErrorMessage());
                return $this->json_string($response->get());
            }
            $key_value = $this->get_record_id();
            $model_data[$primary_key]['value'] = $key_value;
            $this->setRequestValue($primary_key, $key_value);
        } else {
            //$this->writeLog('key value not 0 = '.$key_value);

            $model_data = $model_object->load_by_id($key_value);
            $model_data = $data_model->init_model_data_from_request($model_data);
            $model_data[$primary_key]['value'] = $key_value;
        }
        //$this->writeLog('key_value after = '.$key_value);

        $this->setRequestValue('do', 'edit_done');

        //$this->writeLog(var_export($model_data, true));
        if ($model_object->getError()) {
            $response = new API_Response('error', $model_object->GetErrorMessage());
            return $this->json_string($response->get());
        }
        if (!$model_data) {
            $response = new API_Response('error', 'record not found');
            return $this->json_string($response->get());
        }

        if (count($ql_items) > 0) {
            //$this->writeArrayLog($ql_items);
            //$this->writeArrayLog($_POST);
            //$this->writeArrayLog($model_data['price']);
            //$this->writeLog('after first init');
            //$this->writeArrayLog($model_data['price']);
            //$this->writeLog('second init');

            foreach ($ql_items as $key => $value) {
                //$model_data[$key]['value'] = $value;
                if ($only_ql and $key != $primary_key) {
                    $new_model[$model_name][$key] = $model_object->data_model[$model_name][$key];
                }
            }
            if ($only_ql) {
                $new_model[$model_name][$primary_key] = $model_object->data_model[$model_name][$primary_key];
                $model_object->data_model = $new_model;
                foreach ($model_data as $key => $item) {
                    if (!isset($new_model[$model_name][$key]) and $key != $primary_key) {
                        unset($model_data[$key]);
                    }
                }
            }
        }

        $model_data = $model_object->_before_check_action($model_data, 'edit');
        //$this->writeArrayLog($model_data);

        if (!$model_object->check_data($model_data)) {
            $response = new API_Response('error', $model_object->GetErrorMessage());
        } else {
            $model_data = $model_object->_before_edit_done_action($model_data);
            $model_object->edit_data($model_data, 0, $key_value);

            if ($model_object->getError()) {
                $response = new API_Response('error', $model_object->GetErrorMessage());
            } else {
                if ($this->getConfigValue('apps.realtylog.enable')) {
                    require_once SITEBILL_DOCUMENT_ROOT . '/apps/realtylog/admin/admin.php';
                    $Logger = new realtylog_admin();
                    $Logger->addLog($model_data, $user_id, 'edit', 'data');
                }
                if ($this->getConfigValue('apps.realtylogv2.enable')) {
                    require_once SITEBILL_DOCUMENT_ROOT . '/apps/realtylogv2/admin/admin.php';
                    $Logger = new realtylogv2_admin();
                    $Logger->addLog($model_data, $user_id, 'edit', 'data', 'id');
                }


                $response = new API_Response('success', 'edit ql complete');
            }
        }

        return $this->json_string($response->get());
    }

    function set_record_id($record_id) {
        $this->record_id = $record_id;
    }

    function get_record_id() {
        return $this->record_id;
    }

    /**
     * Создание пустой записи и возвращаем ИД новой записи
     */
    public function _new_empty_record() {
        require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/model/model.php');
        $data_model = new Data_Model();

        $model_name = $this->request->get('model_name');
        $user_id = $this->get_my_user_id();
        $permission = new Permission();
        if (!$permission->get_access($user_id, $model_name, 'access')) {
            $response = new API_Response('error', _e('Доступ запрещен'));
            return $this->json_string($response->get());
        }

        //$this->writeLog(var_export($ql_items, true));

        $model_object = $this->init_custom_model_object($model_name);
        $model_data = $model_object->data_model[$model_name];
        $model_data = $data_model->init_model_data_from_request($model_data);

        //$this->writeLog(var_export($model_data, true));
        if ($model_object->getError()) {
            $this->riseError($model_object->GetErrorMessage());
            $response = new API_Response('error', $model_object->GetErrorMessage());
            return $this->json_string($response->get());
        }
        if (!$model_data) {
            $response = new API_Response('error', 'model not found');
            return $this->json_string($response->get());
        }

        if (count($model_data) > 0) {
            foreach ($model_data as $key => $value) {
                $model_data[$key]['required'] = 'off';
            }
        }
        $new_record_id = $model_object->add_data($model_data, 0);
        if ($model_object->getError()) {
            $this->riseError($model_object->GetErrorMessage());
            $response = new API_Response('error', $model_object->GetErrorMessage());
        } else {
            //Если запись создалась, то вернем массив того что создалось
            $model_data = $model_object->load_by_id($new_record_id);
            $this->set_record_id($new_record_id);

            $response = new API_Response('success', $model_data);
        }
        return $this->json_string($response->get());
    }

    public function _delete_selection() {
        //$data_id = $this->getRequestValue('id');
        $data = json_decode(file_get_contents('php://input'), true);
        //$this->writeLog(__METHOD__ . var_export($data, true));

        $permission = new Permission();
        if (!$permission->get_access($user_id, 'data', 'access')) {
            $response = new API_Response('error', _e('Доступ запрещен'));
            return $this->json_string($response->get());
        }

        require_once (SITEBILL_DOCUMENT_ROOT . '/apps/apiproxy/admin/crm_object.php');
        $crm_object = new crm_object();
        $crm_object->delete_selection($this->get_placement(), $this->get_crm_item_id(), $this->get_site(), $this->get_site_user_id(), $data['item_id']);

        $selected = $crm_object->load_selected($this->get_placement(), $this->get_crm_item_id(), $this->get_site(), $this->get_site_user_id());
        $ret = array('selected' => $selected);
        return $this->json_string($ret);
    }

    function get_site_user_id() {
        return 1;
    }

    function get_site() {
        return 'test.ru';
    }

    function get_placement() {
        if ($_SESSION['PLACEMENT'] != '') {
            return $_SESSION['PLACEMENT'];
        } else {
            return 'SITEBILL_DEV_PLACEMENT';
        }
    }

    function get_crm_item_id() {
        if ($_SESSION['PLACEMENT_OPTIONS'] != '') {
            $ar = json_decode($_SESSION['PLACEMENT_OPTIONS']);
            return $ar->ID;
        } else {
            return 111;
        }
    }

    public function _set_user_id_for_client() {
        $client_id = $this->request->get('client_id');
        $user_id = $this->get_my_user_id();

        $permission = new Permission();
        if (!$permission->get_access($user_id, 'client', 'access')) {
            $response = new API_Response('error', _e('Доступ запрещен'));
            return $this->json_string($response->get());
        }


        $model_object = $this->init_custom_model_object('client');
        $client_data = $model_object->load_by_id($client_id);
        if ($client_data['user_id']['value'] != 0 and $client_data['user_id']['value'] != $user_id) {
            return $this->request_failed('client_already_has_owner');
        }
        if ($client_data['user_id']['value'] != 0 and $client_data['user_id']['value'] == $user_id) {
            $client_data['user_id']['value'] = 0;
        } else {
            $client_data['user_id']['value'] = $user_id;
        }
        $model_object->edit_data($client_data, 0, $client_id);
        if ($model_object->getError()) {
            return $this->request_failed($model_object->GetErrorMessage());
        }
    }

    public function load_meta($model_name, $user_id) {
        $action = $this->get_grid_action_code($model_name, $user_id);
        $DBC = DBC::getInstance();
        $query = 'SELECT meta FROM ' . DB_PREFIX . '_table_grids WHERE action_code=?';
        $stmt = $DBC->query($query, array($action));
        if (!$stmt) {
            return false;
        }
        $ar = $DBC->fetch($stmt);

        $result = array();
        $meta_array = (array) json_decode($ar['meta']);
        if (count($meta_array) > 0) {
            foreach ($meta_array as $key => $item) {
                if (is_object($item)) {
                    $result[$key] = (array) $item;
                } else {
                    $result[$key] = $item;
                }
            }
        }

        return $result;
    }

    public function _update_column_meta() {
        $model_name = $this->request->get('model_name');
        $column_name = $this->request->get('column_name');
        $key = $this->request->get('key');
        $params = $this->request->get('params');
        $user_id = $this->get_my_user_id();

        $action = $this->get_grid_action_code($model_name, $user_id);
        $current_meta = $this->load_meta($model_name, $user_id);
        //$this->writeLog('$current_meta');
        //$this->writeArrayLog($current_meta);
        //$response = new API_Response('success', 'true', $current_meta);
        //return $this->json_string($response->get());



        $DBC = DBC::getInstance();
        if (count($params) > 0) {
            if ($column_name != '') {
                $current_meta[$key][$column_name] = $params;
            } else {
                $current_meta[$key] = $params;
            }
            $query = 'INSERT INTO ' . DB_PREFIX . '_table_grids (`action_code`, `meta`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `meta`=?';
            $stmt = $DBC->query($query, array($action, json_encode($current_meta), json_encode($current_meta)));
        }
        if (!$stmt) {
            return $this->request_failed('update format_grid meta failed: ' . $DBC->getLastError());
        }
        $response = new API_Response('success', 'true');
        return $this->json_string($response->get());
    }

    private function update_meta_value_by_key($model_name, $user_id, $key, $value) {
        $current_meta = $this->load_meta($model_name, $user_id);
        $action = $this->get_grid_action_code($model_name, $user_id);

        $DBC = DBC::getInstance();
        $current_meta[$key] = $value;
        $query = 'INSERT INTO ' . DB_PREFIX . '_table_grids (`action_code`, `meta`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `meta`=?';
        $stmt = $DBC->query($query, array($action, json_encode($current_meta), json_encode($current_meta)));

        if (!$stmt) {
            return false;
        }
        return true;
    }

    public function _get_data() {
        $model_name = $this->request->get('model_name');
        $owner = $this->request->get('owner');
        $input_params = $this->request->get('params');
        $page = $this->request->get('page');
        $per_page = $this->request->get('per_page');
        $user_id = $this->get_my_user_id();
        //$this->writeArrayLog($input_params);
        $load_collections = false;
        $only_collections = false;
        if ( isset($input_params['load_collections']) ) {
            require_once SITEBILL_DOCUMENT_ROOT . '/apps/memorylist/admin/memory_list.php';
            $ML = new Memory_List();
            
            $load_collections = true;
            $collections_domain = $input_params['collections_domain'];
            $collections_deal_id = $input_params['collections_deal_id'];
            if ( isset($input_params['only_collections']) ) {
                $only_collections = true;
                //unset($input_params['only_collections']);
            }
            $this->writeLog($collections_deal_id);
            $this->writeLog($only_collections);
            unset($input_params['load_collections']);
            unset($input_params['collections_domain']);
            unset($input_params['collections_deal_id']);
        }
        $this->writeArrayLog($input_params);


        if ($model_name == '') {
            $model_name = 'data';
        }
        if ($model_name != '') {

            $permission = new Permission();

            //@todo надо решить как быть с anonymous доступом, чтобы могли получать доступ гости к таблице объявлений, например.
            if (!$permission->get_access($user_id, $model_name, 'access')) {
                $response = new API_Response('error', _e('Доступ запрещен'));
                return $this->json_string($response->get());
            }

            $customentity_admin = $this->init_custom_model_object($model_name);
            foreach ($customentity_admin->data_model[$model_name] as $model_item_array) {
                $columns[] = $model_item_array;
            }
            //$this->writeLog(json_encode($columns));
            //$this->writeLog('<pre>'. var_export($ee, true).'</pre>');
            //$this->writeArrayLog($columns);
            $columns_index = $this->indexing_columns($columns);
            $grid_columns = $this->get_columns($model_name, $user_id);
            if (!$grid_columns) {
                //$grid_columns = array_keys($columns_index['index']);
            }
            //$this->writeLog('$grid_columns');
            //$this->writeArrayLog($grid_columns);
            if ($per_page == 0 and isset($grid_columns['meta']['per_page'])) {
                $per_page = $grid_columns['meta']['per_page'];
            } elseif ($per_page == 0) {
                $per_page = $this->getConfigValue('per_page');
            }


            if ($this->request->get('grid_item')) {
                $params['grid_item'] = $this->request->get('grid_item');
                //Принудительно добавляем в список колонок primary key
                array_push($params['grid_item'], $customentity_admin->primary_key);
                if (isset($customentity_admin->data_model[$model_name]['active'])) {
                    array_push($params['grid_item'], 'active');
                }
                if (isset($customentity_admin->data_model[$model_name]['hot'])) {
                    array_push($params['grid_item'], 'hot');
                }
            } else {
                $params['grid_item'] = array_slice($columns_index['default_columns_list'], 0, 7);
                if (!$grid_columns) {
                    $grid_columns['grid_fields'] = $params['grid_item'];
                }
            }

            if ($model_name == 'client') {
                if ($owner) {
                    $user_id = $this->get_my_user_id();
                    $default_params['render_user_id'] = $user_id;
                    $params['grid_conditions']['user_id'] = $user_id;
                } else {
                    //$params['grid_conditions']['user_id'] = 0;
                }
            }
            if ((1 === (int) $this->getConfigValue('check_permissions')) && ($_SESSION['current_user_group_name'] !== 'admin') && (1 === (int) $this->getConfigValue('data_adv_share_access')) and $model_name == 'data') {
                $params['grid_conditions']['user_id'] = $user_id;
            }
            $params['page'] = $page;
            $params['per_page'] = $per_page;
            //Переопределяем параметры если они пришли к нам из запроса

            if (count($input_params) > 0) {
                foreach ($input_params as $key => $value) {
                    if ($key == 'price_min' or $key == 'price_max') {
                        if ($key == 'price_min') {
                            $params['grid_conditions_sql'][$key] = '`'.DB_PREFIX.'_'.$model_name.'`.`price` >= ' . (int) $value;
                        }
                        if ($key == 'price_max') {
                            $params['grid_conditions_sql'][$key] = '`'.DB_PREFIX.'_'.$model_name.'`.`price` <= ' . (int) $value;
                        }
                    } elseif ($key == 'concatenate_search') {
                        $concatenate_condition = $this->compile_concatenate_condition($model_name, $customentity_admin->data_model[$model_name], $value);
                        $params['grid_conditions_left_join'] = $this->compile_concatenate_condition_left_join($model_name, $customentity_admin->data_model[$model_name], $value);
                        if ($concatenate_condition) {
                            $params['grid_conditions_sql']['concatenate_search'] = $concatenate_condition;
                            if (is_array($params['grid_conditions_left_join']['where'])) {
                                $params['grid_conditions_sql']['concatenate_search'] = ' ('.$params['grid_conditions_sql']['concatenate_search'].' OR '.' ( '.implode(' OR ', $params['grid_conditions_left_join']['where']).' ) '.') ';
                            }
                        }

                    } elseif ($customentity_admin->data_model[$model_name][$key]['type'] == 'uploads') {
                        $ignore_uploads_condition = false;
                        if ( is_array($value) ) {
                            if ( count($value) > 1 ) {
                                $ignore_uploads_condition = true;
                            } elseif (in_array(1, $value)) {
                                $only_image_condition = true;
                            } else {
                                $without_image_condition = true;
                            }
                        } else {
                            if ($value == 1) {
                                $only_image_condition = true;
                            } else {
                                $without_image_condition = true;
                            }
                        }
                        if ($only_image_condition == 1) {
                            $condition_uploads = " not in ('', 'a:0:{}') ";
                            $uploads_null_condidtion .= " AND `".DB_PREFIX."_{$model_name}`.`{$customentity_admin->data_model[$model_name][$key]['name']}` IS NOT NULL ";
                        } else {
                            $condition_uploads = " in ('', 'a:0:{}' ) ";
                            $uploads_null_condidtion .= " OR  `".DB_PREFIX."_{$model_name}`.`{$customentity_admin->data_model[$model_name][$key]['name']}` IS NULL ";
                        }

                        if ( !$ignore_uploads_condition ) {
                            $params['grid_conditions_sql'][$key] = "( `".DB_PREFIX."_{$model_name}`.`{$customentity_admin->data_model[$model_name][$key]['name']}` " . $condition_uploads . $uploads_null_condidtion . " ) ";
                        }
                    } elseif ($customentity_admin->data_model[$model_name][$key]['type'] == 'dtdatetime') {
                        if ($value['startDate'] != NULL and $value['endDate'] != NULL) {
                            $params['grid_conditions_sql'][$key] = "( `".DB_PREFIX."_{$model_name}`.`$key` >= '" . date('Y-m-d', strtotime($value['startDate'])) . "' and `".DB_PREFIX."_{$model_name}`.`$key` <= '" . date('Y-m-d', strtotime($value['endDate'])) . " 23:59:59') ";
                        }
                    } elseif ($customentity_admin->data_model[$model_name][$key]['type'] == 'date') {
                        if ($value['startDate'] != NULL and $value['endDate'] != NULL) {
                            $params['grid_conditions_sql'][$key] = "( `".DB_PREFIX."_{$model_name}`.`$key` >= " . strtotime($value['startDate']) . " and `".DB_PREFIX."_{$model_name}`.`$key` <= " . strtotime($value['endDate']) . ") ";
                        }
                    } elseif ($key == 'only_collections') {
                        $collections_ids = $ML->getUserMemoryLists_indexed_by_data_id($user_id, $collections_domain, $collections_deal_id);
                        $this->writeArrayLog($collections_ids);
                        if (is_array($collections_ids) and count($collections_ids) > 0 ) {
                            $params['grid_conditions_sql']['collections_ids'] = "`".DB_PREFIX."_{$model_name}`.`".$customentity_admin->primary_key."` in (". implode(',', array_keys($collections_ids)).") ";
                        } else {
                            $params['grid_conditions_sql']['collections_ids'] = "`".DB_PREFIX."_{$model_name}`.`".$customentity_admin->primary_key."` in (null) ";
                        }
                        unset($input_params['only_collections']);
                    } else {
                        $params['grid_conditions'][$key] = $value;
                    }
                }
            }

            //$this->writeLog('<h1>params</h1>');
            //$this->writeArrayLog($params);

            $rows = $customentity_admin->grid_array($params, $default_params);

            if ($customentity_admin->getError()) {
                $response = new API_Response('error', $customentity_admin->GetErrorMessage());
                return $this->json_string($response->get());
            }

            //$this->writeLog('<b>current</b>');
            $rows_index = $this->indexing_rows($rows, $customentity_admin->primary_key);
            if ( $load_collections ) {
                $rows = $ML->parse_memory_list($collections_domain, $collections_deal_id, $user_id, $customentity_admin->primary_key, $rows);
            }


            //$this->writeArrayLog($customentity_admin->data_model[$model_name]);
            //$columns = array_values($customentity_admin->data_model[$model_name]);

            $ret = array(
                'id' => $model_id,
                'name' => $model_name,
                'per_page' => $per_page,
                'total_count' => $customentity_admin->get_total_count(),
                'columns' => $columns,
                'columns_index' => $columns_index['index'],
                'rows_index' => $rows_index['index'],
                'default_columns_list' => $columns_index['default_columns_list'],
                'grid_columns' => $grid_columns,
                'rows' => $rows,
            );
            //$this->writeLog(__METHOD__ . ', name = ' . $model_name);
            //$this->writeLog(__METHOD__ . ', rows = <pre>' . var_export($ret, true) . '</pre>');
            $result = $this->json_string($ret);
            //$this->writeLog($result);
            return $result;
        }
        return $this->request_failed('model not defined');
    }

    private function compile_concatenate_condition($model_name, $data_model, $value) {
        $concatenate_columns = array();
        foreach ($data_model as $key => $item) {
            if (in_array($item['type'], array('primary_key', 'mobilephone', '', 'safe_string', 'textarea', 'textarea_editor')) and $item['dbtype'] != 'notable') {
                $concatenate_columns[] = '`' .DB_PREFIX.'_'. $model_name . '`.'.'`' . $key . '`';
            }
        }
        if (count($concatenate_columns) > 0) {
            $value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            $value = str_replace(array('(', ')', '*', ',', '`'), '', $value);

            $columns = implode(',', $concatenate_columns);
            return "( concat({$columns}) like '%{$value}%' ) ";
        }
        return false;
    }

    private function compile_concatenate_condition_left_join($model_name, $data_model, $value) {
        $left_joins = array();
        $value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
        $value = str_replace(array('(', ')', '*', ',', '`'), '', $value);

        foreach ($data_model as $key => $item) {
            if ( $item['dbtype'] != 'notable' ) {
                if (in_array($item['type'], array('select_by_query'))) {
                    $left_joins['tables'][] = ' LEFT JOIN `'.DB_PREFIX.'_'.$item['primary_key_table'].'` on (`'.DB_PREFIX.'_'.$item['primary_key_table'].'`.`'.$item['primary_key_name'].'`=`' .DB_PREFIX.'_'. $model_name . '`.`'.$item['name'].'`) ';
                    $left_joins['where'][] = ' ( `'.DB_PREFIX.'_'.$item['primary_key_table'].'`.`'.$item['value_name'].'` like \'%'.$value.'%\' ) ';
                } elseif ($item['type'] == 'select_box_structure') {
                    $left_joins['tables'][] = ' LEFT JOIN `'.DB_PREFIX.'_topic` on (`'.DB_PREFIX.'_topic`.`id`=`' .DB_PREFIX.'_'. $model_name . '`.`topic_id`) ';
                    $left_joins['where'][] = ' ( `'.DB_PREFIX.'_topic`.`name` like \'%'.$value.'%\' ) ';
                }
            }
        }
        return $left_joins;
    }


    private function get_grid_action_code($model_name, $user_id) {
        $action = $model_name . '_user_' . $user_id;

        return $action;
    }

    public function _format_grid() {
        $model_name = $this->request->get('model_name');
        $grid_items = $this->request->get('grid_items');
        $per_page = $this->request->get('per_page');
        $user_id = $this->get_my_user_id();

        $action = $this->get_grid_action_code($model_name, $user_id);

        $DBC = DBC::getInstance();
        if (count($grid_items) > 0) {
            $query = 'INSERT INTO ' . DB_PREFIX . '_table_grids (`action_code`, `grid_fields`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `grid_fields`=?';
            $stmt = $DBC->query($query, array($action, json_encode($grid_items), json_encode($grid_items)));
        } else {
            $query = 'DELETE FROM ' . DB_PREFIX . '_table_grids WHERE `action_code`=?';
            $stmt = $DBC->query($query, array($action));
        }
        if (!$stmt) {
            return $this->request_failed('update format_grid failed');
        }
        $response = new API_Response('success', 'true');
        return $this->json_string($response->get());
    }

    private function indexing_columns($columns) {
        foreach ($columns as $idx => $item) {
            $ra['index'][$item['name']] = $idx;
            $ra['default_columns_list'][] = $item['name'];
        }
        return $ra;
    }

    private function indexing_rows($rows, $primary_key) {
        foreach ($rows as $idx => $item) {
            $ra['index'][$item[$primary_key]['value']] = $idx;
        }
        return $ra;
    }

    public function _get_max() {
        $model_name = $this->request->get('model_name');
        $columnName = $this->request->get('columnName');
        $DBC = DBC::getInstance();
        $query = "select max($columnName) as maximum from " . DB_PREFIX . "_$model_name";
        $stmt = $DBC->query($query, array());
        if (!$stmt) {
            return $this->request_failed('model not defined');
        }
        $ar = $DBC->fetch($stmt);

        $response = new API_Response('success', $ar['maximum']);
        return $this->json_string($response->get());
    }

}
