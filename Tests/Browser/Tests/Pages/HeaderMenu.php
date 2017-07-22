<?php

require_once 'PageBase.php';

class HeaderMenu extends PageBase {
    const HEADER_ID = 'masthead';
    const HEADER_MENU = 'menu-cart-and-account';
    const CONTACT_US_CSS = '#menu-item-2057 > a';
    const MY_ACCOUNT_CSS = '#menu-item-2054 > a';
    const CART_CSS = '#menu-item-2056 > a';
    const CHECKOUT_CSS = '#menu-item-2055 > a';
    const SEARCH_CSS = 'div.site-topbar-right > div > i';
    const SEARCH_INPUT_CSS = 'div.search-block > form > label > input';
    const MENU_ID = '#site-navigation';

    public function AppearsOnHomePage() {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::HEADER_MENU));
        return true;
    }

    public function ContactUsLinkOpensContactUsPage() {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::HEADER_MENU));

        $link = $menu->findElement(WebDriverBy::cssSelector(self::CONTACT_US_CSS));
        $link->click();

        $this->webDriver->wait(10, 250)->until(WebDriverExpectedCondition::titleContains('Contact Us'));
        return true;
    }

    public function MyAccountLinkOpensMyAccountPage()  {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::HEADER_MENU));

        $link = $menu->findElement(WebDriverBy::cssSelector(self::MY_ACCOUNT_CSS));
        $link->click();

        $this->webDriver->wait(10, 250)->until(WebDriverExpectedCondition::titleContains('My Account'));
        return true;
    }

    public function CartLinkOpensCartPage() {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::HEADER_MENU));

        $link = $menu->findElement(WebDriverBy::cssSelector(self::CART_CSS));
        $link->click();

        $this->webDriver->wait(10, 250)->until(WebDriverExpectedCondition::titleContains('Cart'));
        return true;
    }

    public function CheckoutLinkOpensCheckoutPage() {
        $home = $this->webDriver->get(self::BASE_URL);
        $menu = $home->findElement(WebDriverBy::id(self::HEADER_MENU));

        $link = $menu->findElement(WebDriverBy::cssSelector(self::CHECKOUT_CSS));
        $link->click();

        $this->webDriver->wait(10, 250)->until(WebDriverExpectedCondition::titleContains('Checkout'));
        return true;
    }

    public function SearchLinkOpensSearchInput() {
        $home = $this->webDriver->get(self::BASE_URL);
        $link = $home->findElement(WebDriverBy::cssSelector(self::SEARCH_CSS));
        $link->click();

        $this->webDriver->wait(2, 250)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::cssSelector(self::SEARCH_INPUT_CSS)));
        return true;
    }
}
