<?php
use PHPUnit\Framework\TestCase;
require_once('init_application.php');
class StackTest extends TestCase
{
    public function testPushAndPop()
    {
        $stack = [];
        $this->assertSame(0, count($stack));

        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack)-1]);
        $this->assertSame(1, count($stack));

        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(0, count($stack));
    }

    public function testGetAdminUserID () {
        $sitebill = SiteBill::sitebill_instance();
        $this->assertSame('1', $sitebill->getAdminUserId());
    }
    public function testConfig () {
        $sitebill = SiteBill::sitebill_instance();
        $this->assertSame('rumantic.coder', $sitebill->getConfigValue('smtp1_login'));
    }

}
