<?php

require 'Pages/MyAccountPage.php';

class MyAccountTest extends \PHPUnit\Framework\TestCase {

    public function testMyAccount_Page_Allows_New_User_To_Register() {
        $myAccountPage = new MyAccountPage;
        $myAccountPage->StartTest();

        $this->assertTrue($myAccountPage->testMyAccount_Page_Allows_New_User_To_Register());

        $myAccountPage->EndTest();
    }
}
