<?php
use PHPUnit\Framework\TestCase;
require_once('init_application.php');
use system\lib\SiteBill;
use system\lib\admin\data\Data_Manager;

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

}
