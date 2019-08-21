<?php
use PHPUnit\Framework\TestCase;
require_once('init_application.php');
use system\lib\SiteBill;
use system\lib\admin\data\Data_Manager;
use system\lib\system\user\Login;

class StackTest extends TestCase
{
    public function testGetAdminUserID () {
        $sitebill = SiteBill::sitebill_instance();
        $this->assertSame('1', $sitebill->getAdminUserId());
    }
    public function testConfig () {
        $sitebill = SiteBill::sitebill_instance();
        $this->assertSame('rumantic.coder', $sitebill->getConfigValue('smtp1_login'));
    }
    public function testDataModelLoader () {
        $data_manager = new Data_Manager();
        $this->assertSame('primary_key', $data_manager->data_model['data']['id']['type']);
    }

    /**
     * Для работы этого теста нужно создать пользователя в группе админов
     * Логин: testadmin
     * Пароль: testadmin
     */
    public function testAdminLogin () {
        $login = new Login();
        $login->disable_restore_favorites();
        $this->assertSame(true, $login->checkLogin('testadmin', 'testadmin'));
    }

}
