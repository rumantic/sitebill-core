<?php
use PHPUnit\Framework\TestCase;
require_once('init_application.php');
use system\lib\SiteBill;

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

}
