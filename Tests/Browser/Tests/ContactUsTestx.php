<?php

require 'Pages/ContactUsPage.php';

class ContactUsTest extends \PHPUnit\Framework\TestCase {
    public function testContactUs_Page_Presents_ContactUs_Form_Notifies_Successful_Transmission() {
        $contactUsPage = new ContactUsPage;
        $contactUsPage->StartTest();

        $this->assertTrue($contactUsPage->ContactUs_Page_Presents_ContactUs_Form_Notifies_Successful_Transmission());

        $contactUsPage->EndTest();
    }

    public function testInvalid_ContactUs_Page_Presents_Error_Message() {
        $contactUsPage = new ContactUsPage;
        $contactUsPage->StartTest();

        $this->assertTrue($contactUsPage->Invalid_ContactUs_Page_Presents_Error_Message());

        $contactUsPage->EndTest();
    }
}
