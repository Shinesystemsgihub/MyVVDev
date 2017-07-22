<?php

require_once 'PageBase.php';

class FooterMenu extends PageBase {
    const HEADER_ID = 'masthead';
    const HEADER_MENU = 'menu-cart-and-account';
    const FOOTER_MENU_ID = 'menu-footer-menu';
    const CONTACT_US_CSS = '#menu-item-985 > a';
    const MY_ACCOUNT_CSS = '#menu-item-986 > a';
    const CART_CSS = '#menu-item-1968 > a';
    const CHECKOUT_CSS = '#menu-item-987 > a';
    const SEARCH_CSS = 'div.site-topbar-right > div > i';
    const SEARCH_INPUT_CSS = 'div.search-block > form > label > input';
    const MENU_ID = '#site-navigation';

    public function AppearsOnHomePage() {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::FOOTER_MENU_ID));
        return true;
    }

    public function ContactUsLinkOpensContactUsPage() {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::FOOTER_MENU_ID));

        $link = $menu->findElement(WebDriverBy::cssSelector(self::CONTACT_US_CSS));
        $link->click();

        $this->webDriver->wait(10, 250)->until(WebDriverExpectedCondition::titleContains('Contact Us'));
        return true;
    }

     public function MyAccountLinkOpensFailsToOpenMyAccountPageWithoutLogin() {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::FOOTER_MENU_ID));

        $link = $menu->findElement(WebDriverBy::cssSelector(self::MY_ACCOUNT_CSS));
        $link->click();

        $this->webDriver->wait(10, 250)->until(WebDriverExpectedCondition::titleContains('My Account'));
        return true;
    }

    public function CartLinkOpensCartPage() {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::FOOTER_MENU_ID));

        $link = $menu->findElement(WebDriverBy::cssSelector(self::CART_CSS));
        $link->click();

        $this->webDriver->wait(10, 250)->until(WebDriverExpectedCondition::titleContains('Cart'));
        return true;
    }

    public function CheckoutLinkFailsToOpenCheckoutPageWithoutLogin() {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::FOOTER_MENU_ID));

        $link = $menu->findElement(WebDriverBy::cssSelector(self::CHECKOUT_CSS));
        $link->click();

        $this->webDriver->wait(10, 250)->until(WebDriverExpectedCondition::titleContains('Checkout'));
        return true;
    }
}
