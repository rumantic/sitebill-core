<?php
require_once('init_application.php');
$sitebill = SiteBill::sitebill_instance();
echo 'admin user_id = '.$sitebill->getAdminUserId()."\n";
