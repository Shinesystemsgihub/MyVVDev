<?php

require 'Pages/FooterMenu.php';

class FooterMenuTest extends \PHPUnit\Framework\TestCase {

    public function testFooterMenuAppearsOnHomePage() {
        $footerMenu = new FooterMenu;
        $footerMenu->StartTest();

        $this->assertTrue($footerMenu->AppearsOnHomePage(), 'Navigation menu on home page missing.');
        
        $footerMenu->EndTest();
    }

    public function testContactUs_Navigation() {
        $footerMenu = new FooterMenu;
        $footerMenu->StartTest();

        $this->assertTrue($footerMenu->ContactUsLinkOpensContactUsPage());
        
        $footerMenu->EndTest();
    }

    // public function testMyAccounts_Navigation() {
    //     $footerMenu = new FooterMenu;
    //     $footerMenu->StartTest();

    //     $this->assertTrue($footerMenu->MyAccountLinkOpensFailsToOpenMyAccountPageWithoutLogin());
        
    //     $footerMenu->EndTest();
    // }

    // public function testCart_Navigation() {
    //     $footerMenu = new FooterMenu;
    //     $footerMenu->StartTest();

    //     $this->assertTrue($footerMenu->CartLinkOpensCartPage());
        
    //     $footerMenu->EndTest();
    // }

    // public function testCheckout_Navigation() {
    //     $footerMenu = new FooterMenu;
    //     $footerMenu->StartTest();

    //     $this->assertTrue($footerMenu->CheckoutLinkFailsToOpenCheckoutPageWithoutLogin());
        
    //     $footerMenu->EndTest();
    // }

    // public function testSearch_Navigation() {
    //     $footerMenu = new FooterMenu;
    //     $footerMenu->StartTest();

    //     $this->assertTrue($footerMenu->SearchLinkOpensSearchInput());
        
    //     $footerMenu->EndTest();
    // }
}
