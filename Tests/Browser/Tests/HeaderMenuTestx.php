<?php

require 'Pages/HeaderMenu.php';

class HeaderMenuTest extends \PHPUnit\Framework\TestCase {

    public function testHeaderMenuAppearsOnHomePage() {
        $headerMenu = new HeaderMenu;
        $headerMenu->StartTest();

        $this->assertTrue($headerMenu->AppearsOnHomePage(), 'Home page or header menu on home page missing.');
        
        $headerMenu->EndTest();
    }

    public function testContactUs_Navigation() {
        $headerMenu = new HeaderMenu;
        $headerMenu->StartTest();

        $this->assertTrue($headerMenu->ContactUsLinkOpensContactUsPage(), 'Header menu fails to open Contact Us page.');
        
        $headerMenu->EndTest();
    }

    public function testMyAccounts_Navigation() {
        $headerMenu = new HeaderMenu;
        $headerMenu->StartTest();

        $this->assertTrue($headerMenu->MyAccountLinkOpensMyAccountPage(), 'Header menu fails to open My Account page.');
        
        $headerMenu->EndTest();
    }

    public function testCart_Navigation() {
        $headerMenu = new HeaderMenu;
        $headerMenu->StartTest();

        $this->assertTrue($headerMenu->CartLinkOpensCartPage(), 'Header menu fails to open Cart page.');
        
        $headerMenu->EndTest();
    }

    public function testCheckout_Navigation() {
        $headerMenu = new HeaderMenu;
        $headerMenu->StartTest();

        $this->assertTrue($headerMenu->CheckoutLinkOpensCheckoutPage(), 'Header menu fails to open Checkout page.');
        
        $headerMenu->EndTest();
    }

    public function testSearch_Navigation() {
        $headerMenu = new HeaderMenu;
        $headerMenu->StartTest();

        $this->assertTrue($headerMenu->SearchLinkOpensSearchInput(), 'Search link fails to open Search input.');
        
        $headerMenu->EndTest();
    }
}
